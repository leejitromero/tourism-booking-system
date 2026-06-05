<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\TourPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class AdminController extends Controller
{
    public function dashboard()
    {
        $monthlyRevenue = Payment::where('payment_status', 'Paid')->sum('amount');
        return view('admin.dashboard', [
            'packages' => TourPackage::count(),
            'bookings' => Booking::count(),
            'pending' => Booking::where('status', 'Pending')->count(),
            'approved' => Booking::where('status', 'Approved')->count(),
            'completed' => Booking::where('status', 'Completed')->count(),
            'revenue' => $monthlyRevenue,
            'recentBookings' => Booking::with(['user','package'])->latest()->take(5)->get(),
        ]);
    }

    public function bookings()
    {
        $bookings = Booking::with(['user', 'package', 'payment'])->latest()->get();
        return view('admin.bookings', compact('bookings'));
    }

    public function updateBooking(Request $request, Booking $booking)
    {
        $data = $request->validate(['status' => 'required|in:Pending,Approved,Rejected,Completed']);
        DB::transaction(function () use ($booking, $data) {
            $oldStatus = $booking->status;
            $booking->update($data);

            if ($oldStatus !== 'Approved' && $data['status'] === 'Approved') {
                $booking->package?->decrement('slots', min($booking->people_count, $booking->package->slots));
            }

            if ($booking->payment) {
                $booking->payment->update([
                    'payment_status' => in_array($data['status'], ['Approved', 'Completed']) ? 'Paid' : 'Pending',
                ]);
            }
        });

        return back()->with('success', 'Booking status updated.');
    }

    public function users()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function reports()
    {
        $bookings = Booking::with(['user','package','payment'])->latest()->get();
        return view('admin.reports', compact('bookings'));
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt|max:2048']);
        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            if (!$data || empty($data['title'])) continue;

            TourPackage::updateOrCreate(['title' => $data['title']], [
                'category' => $data['category'] ?? 'Tour',
                'description' => $data['description'] ?? 'Lingayen tourism package.',
                'location' => $data['location'] ?? 'Lingayen, Pangasinan',
                'duration' => $data['duration'] ?? '1 Day',
                'price' => (float)($data['price'] ?? 0),
                'slots' => (int)($data['slots'] ?? 1),
                'image_url' => $data['image_url'] ?? null,
                'distance' => $data['distance'] ?? null,
                'beach_info' => $data['beach_info'] ?? null,
                'stars' => (int)($data['stars'] ?? 0),
                'review_score' => isset($data['review_score']) ? (float)$data['review_score'] : null,
                'review_count' => (int)($data['review_count'] ?? 0),
                'amenities' => $data['amenities'] ?? null,
            ]);
            $count++;
        }
        fclose($handle);

        return back()->with('success', "Imported {$count} package(s) successfully.");
    }

    public function sampleImport()
    {
        return response()->streamDownload(function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['title','category','description','location','distance','beach_info','duration','price','slots','image_url','stars','review_score','review_count','amenities']);
            fputcsv($file, ['Sample Lingayen Resort','Resort','Sample CSV import accommodation','Lingayen','1 km from centre','Beach nearby','1 Night','2500','10','images/places/sample.jpg','3','7.5','12','Pool,Free parking,WiFi']);
            fclose($file);
        }, 'sample-tour-packages.csv', ['Content-Type' => 'text/csv']);
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $bookings = Booking::with(['user','package','payment'])->latest()->get();
        $rows = $this->reportRows($bookings);

        if ($format === 'json') return response()->json($rows);
        if ($format === 'xlsx') return $this->xlsx($rows, 'booking-report.xlsx');
        if ($format === 'pdf') return view('admin.print-report', compact('bookings'));

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) fputcsv($file, $row);
            fclose($file);
        }, 'booking-report.csv', ['Content-Type' => 'text/csv']);
    }

    private function reportRows($bookings): array
    {
        $rows = [['Tourist','Email','Accommodation','Location','Check-in','Check-out','Nights','Guests','Total','Booking Status','Payment Method','Payment Status']];
        foreach ($bookings as $booking) {
            $rows[] = [
                $booking->user->name,
                $booking->user->email,
                $booking->package->title,
                $booking->package->location,
                (string)($booking->check_in_date ?? $booking->booking_date),
                (string)($booking->check_out_date ?? 'N/A'),
                (string)($booking->nights ?? 1),
                (string)$booking->people_count,
                (string)$booking->total_amount,
                $booking->status,
                $booking->payment->payment_method ?? 'N/A',
                $booking->payment->payment_status ?? 'No Payment',
            ];
        }
        return $rows;
    }

    private function xlsx(array $rows, string $filename)
    {
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new ZipArchive();
        $zip->open($tmp, ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Booking Report" sheetId="1" r:id="rId1"/></sheets></workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>');
        $sheet = '<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
        foreach ($rows as $r => $row) {
            $sheet .= '<row r="'.($r+1).'">';
            foreach ($row as $c => $value) {
                $cell = chr(65 + $c) . ($r + 1);
                $sheet .= '<c r="'.$cell.'" t="inlineStr"><is><t>'.htmlspecialchars((string)$value, ENT_XML1).'</t></is></c>';
            }
            $sheet .= '</row>';
        }
        $sheet .= '</sheetData></worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        return response()->download($tmp, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->deleteFileAfterSend(true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class CertificateController extends Controller
{
    public function index()
    {
        return view('certificates.index');
    }

    public function characterCertificate(Student $student)
    {
        return view('certificates.character', compact('student'));
    }

    public function characterCertificatePrint(Student $student)
    {
        $pdf = Pdf::loadView('certificates.character-print', compact('student'))
            ->setPaper('a4');

        return $pdf->stream("character-certificate-{$student->admission_no}.pdf");
    }

    public function leavingCertificate(Student $student)
    {
        return view('certificates.leaving', compact('student'));
    }

    public function leavingCertificatePrint(Student $student)
    {
        $pdf = Pdf::loadView('certificates.leaving-print', compact('student'))
            ->setPaper('a4');

        return $pdf->stream("leaving-certificate-{$student->admission_no}.pdf");
    }

    public function bonafideCertificate(Student $student)
    {
        return view('certificates.bonafide', compact('student'));
    }

    public function bonafideCertificatePrint(Student $student)
    {
        $pdf = Pdf::loadView('certificates.bonafide-print', compact('student'))
            ->setPaper('a4');

        return $pdf->stream("bonafide-certificate-{$student->admission_no}.pdf");
    }

    public function idCard(Student $student)
    {
        $qrDataUri = $this->generateStudentQr($student);
        return view('certificates.id-card', compact('student', 'qrDataUri'));
    }

    public function idCardPrint(Student $student)
    {
        $qrDataUri = $this->generateStudentQr($student);

        $pdf = Pdf::loadView('certificates.id-card-print', compact('student', 'qrDataUri'))
            ->setPaper([0, 0, 242.65, 153.07]); // CR80 card size in points

        return $pdf->stream("id-card-{$student->admission_no}.pdf");
    }

    private function generateStudentQr(Student $student): string
    {
        $qrContent = implode(' | ', array_filter([
            'Name: ' . $student->first_name . ' ' . $student->last_name,
            'Adm: ' . ($student->admission_no ?? ''),
            'Class: ' . ($student->studentClass->name ?? ''),
        ]));

        $qrCode = new QrCode(data: $qrContent, size: 80, margin: 2);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }
}

<?php


namespace App\Component;

use Dompdf\Dompdf;


class GeneratePDFComponent
{
	public static function generateByHtml(string $html)
	{
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);

		$dompdf->setPaper('A4', 'landscape');

		$dompdf->render();

		$dompdf->stream();
	}
}
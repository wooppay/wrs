<?php


namespace App\Service;


use Dompdf\Dompdf;

class GeneratePdfService
{
	public static function generateByHtml(string $html) : void
	{
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();

		$dompdf->loadHtml($html);

		$dompdf->setPaper('A4', 'landscape');

		$dompdf->render();

		$dompdf->stream();
	}
}
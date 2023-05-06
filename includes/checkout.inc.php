<?php
require '../../libraries/fpdf/fpdf.php';
include 'connect.inc.php';

function createPdfFile($userId, $orderId) {
    global $connection;

    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFont('Arial', '', 16);
    $pdf->Cell(40, 10, 'Order ID:');
    $pdf->Cell(40, 10, $orderId);

    $pdf_file = 'order_' . $orderId . '.pdf';
    $pdf->Output('F', $pdf_file);

    $pdf_data = file_get_contents('order-' .$orderId . '.pdf');
    $pdf_data = mysqli_real_escape_string($connection, $pdf_data);

    $sql = "INSERT INTO orders (pdf) VALUES ('$pdf_data')";
    if ($connection->query($sql) === TRUE) {
        echo "PDF file saved to database.";
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }

    echo ' <a href="' . $pdf_file . '">Download PDF</a>';
}

function generateInvoicePDF($method, $customerName, $address, $invoiceNumber, $invoiceDate, $items, $subtotal, $tax, $total) {
    global $connection;
    
    define('EURO',chr(128));

    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFont('Helvetica','B',16);
    $pdf->Cell(190,10,'INVOICE',0,1,'C');

    $pdf->SetFont('Helvetica','',12);
    $pdf->Cell(40,10,'Customer Name:',0,0);
    $pdf->Cell(100,10,$customerName,0,1);

    $pdf->Cell(40,10,'Address:',0,0);
    $pdf->Cell(100,10,$address,0,1);

    $pdf->Cell(40,10,'Invoice Number:',0,0);
    $pdf->Cell(100,10,$invoiceNumber,0,1);

    $pdf->Cell(40,10,'Invoice Date:',0,0);
    $pdf->Cell(100,10,$invoiceDate,0,1);

    $pdf->SetFont('Helvetica','B',12);
    $pdf->Cell(90,10,'Item',1,0,'C');
    $pdf->Cell(30,10,'Price',1,0,'C');
    $pdf->Cell(30,10,'Quantity',1,0,'C');
    $pdf->Cell(40,10,'Total',1,1,'C');

    // make list of every product id
    $productIds = array();
    $pdf->SetFont('Helvetica','',12);
    foreach ($items as $item) {
        for ($i = 0; $i < $item['quantity']; $i++) {
            $productIds[] = $item['id'];
        }
        $pdf->Cell(90, 10, $item['name'], 1,0);
        $pdf->Cell(30, 10, EURO . ' ' . $item['price'], 1,0);
        $pdf->Cell(30, 10, $item['quantity'], 1,0);
        $pdf->Cell(40, 10, EURO . ' ' . $item['price']*$item['quantity'], 1,1);
    }

    $productIdsString = implode(',', $productIds);

    $pdf->Cell(120,10, '' ,0,0);
    $pdf->Cell(30,10, 'Subtotal:', 0,0);
    $pdf->Cell(40,10, EURO . ' ' . $subtotal, 0,1);

    $pdf->Cell(120,10,'',0,0);
    $pdf->Cell(30,10,'BTW (21%):',0,0);
    $pdf->Cell(40,10, EURO . ' ' . $tax,0,1);

    $pdf->SetFont('Helvetica','B',12);
    $pdf->Cell(120,10,'',0,0);
    $pdf->Cell(30,10,'Total:',0,0);
    $pdf->Cell(40,10, EURO . ' ' . $total,0,1);

    // output PDF to browser
    $pdf_file = $invoiceNumber . '.pdf';
    $pdf->Output('F', '../../orders/' . $pdf_file);
    
    if (!($method == "paypal")) {
        echo '
        <div class="card">
            <h2>Order placed</h2>
            <p>Your order has been placed successfully. You can download your invoice <a href="../../orders/' . $pdf_file . '" target="_blank">here</a>.</p>
            <p>Order ID: ' . $invoiceNumber . '</p>
            <p>Order date: ' . $invoiceDate . '</p>
            <p>Order address: ' . $address . '</p>
        </div>
        ';
    }

    $pdf_data = file_get_contents('../../orders/' .$invoiceNumber . '.pdf');
    $pdf_data = mysqli_real_escape_string($connection, $pdf_data);


    $sql = "INSERT INTO orders (id, user, products, status, pdf, date_added) VALUES ('" . $invoiceNumber . "', '". $_SESSION["userid"] . "', '" . $productIdsString . "', 0, '" . $pdf_data . "', default)";
    $connection->query($sql);
}
/* Copyright (c) 2023 Cédric Verlinden. All rights reserved. */
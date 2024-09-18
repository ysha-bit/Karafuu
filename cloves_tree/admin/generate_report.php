<?php
// Include the TCPDF library
require_once('../tcpdf/tcpdf.php');
include('../conn/config.php');

// Create a new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Your Name');
$pdf->setTitle('Clove Trees Annual Report');
$pdf->setSubject('Annual Report');
$pdf->setKeywords('TCPDF, PDF, report, annual, clove, trees');

// Set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Clove Trees Annual Report', 'Generated Report');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page to the PDF
$pdf->addPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Title
$pdf->Cell(0, 10, 'Clove Trees Annual Report', 0, 1, 'C');

// Report description
$pdf->Ln(5);
$pdf->Write(0, 'This report summarizes the clove trees provided, trees that were lost, available trees, and the number of farmers given clove trees per year.', '', 0, 'L', true, 0, false, false, 0);

// Line break
$pdf->Ln(5);

// Retrieve data for available trees
$sqlAvailableTrees = "SELECT 
                        YEAR(progress_date) AS year, 
                        SUM(number_tree) AS available_trees
                     FROM 
                        treeprogress
                     GROUP BY 
                        YEAR(progress_date)";

$resultAvailableTrees = mysqli_query($conn, $sqlAvailableTrees);

if (!$resultAvailableTrees) {
    die("Error executing query: " . mysqli_error($conn));
}

// Retrieve data for provided trees
$sqlProvidedTrees = "SELECT 
                        date AS year, 
                        SUM(amount_of_miche) AS tree_provided
                     FROM 
                        farmers
                     GROUP BY 
                        date";

$resultProvidedTrees = mysqli_query($conn, $sqlProvidedTrees);

if (!$resultProvidedTrees) {
    die("Error executing query: " . mysqli_error($conn));
}

// Initialize arrays to store results
$availableTreesArray = [];
$providedTreesArray = [];

// Populate available trees array
while ($row = mysqli_fetch_assoc($resultAvailableTrees)) {
    $availableTreesArray[$row['year']] = $row['available_trees'];
}

// Populate provided trees array
while ($row = mysqli_fetch_assoc($resultProvidedTrees)) {
    $providedTreesArray[$row['year']] = $row['tree_provided'];
}

// Initialize the HTML table string
$html = '<table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Tree Provided</th>
                    <th>Available Trees</th>
                    <th>Tree Lost</th>
                </tr>
            </thead>
            <tbody>';

// Variables to hold total values
$totalTreeProvided = 0;
$totalAvailableTrees = 0;
$totalTreeLost = 0;

// Populate table rows
foreach ($providedTreesArray as $year => $treeProvided) {
    $availableTrees = isset($availableTreesArray[$year]) ? $availableTreesArray[$year] : 0;
    $treeLost = $treeProvided - $availableTrees;

    // Summing up the totals
    $totalTreeProvided += $treeProvided;
    $totalAvailableTrees += $availableTrees;
    $totalTreeLost += $treeLost;

    $html .= "<tr>
                <td>$year</td>
                <td>$treeProvided</td>
                <td>$availableTrees</td>
                <td>$treeLost</td>
              </tr>";
}

// Add the totals row
$html .= "<tr>
            <th>Total</th>
            <th>$totalTreeProvided</th>
            <th>$totalAvailableTrees</th>
            <th>$totalTreeLost</th>
          </tr>";

$html .= '</tbody></table>';

// Output the table in the PDF
$pdf->writeHTML($html, true, false, false, false, '');

// Close and output the PDF document
$pdf->Output('clove_trees_report.pdf', 'I');

// Close the database connection
mysqli_close($conn);
?>

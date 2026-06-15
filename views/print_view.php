<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Logbook - <?php echo htmlspecialchars($current_category ?: 'All'); ?></title> 
    <link rel="stylesheet" href="assets/css/print_style.css">
</head>
<body>

    <div class="no-print">
    <button onclick="window.print()" style="display: inline-flex; align-items: center; justify-content: center; gap: 12px; padding: 12px 24px; background: #2773ae; color: white; border: none; cursor: pointer; border-radius: 8px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: background 0.3s ease;">
    <img src="assets/img/print.png" alt="Print" style="width: 20px; height: 20px;"> 
    <span>Confirm Print / Save as PDF</span>
</button>
        <button onclick="window.location.href='index.php'" style="padding: 10px 20px; background: #7f8c8d; color: white; border: none; cursor: pointer; border-radius: 4px; font-size: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
            ✖ Close Tab
        </button>
    </div>

    <div class="document-page">
        <div class="official-header">
            <img src="assets/img/logo.png" alt="DOST-SEI Logo">
            <div class="official-header-text">
                <h1>SCIENCE EDUCATION INSTITUTE</h1>
                <p>1st and 2nd Levels, Science Heritage Building, DOST Compound, General Santos Avenue, Bicutan, Taguig City</p>
                <p>Telephone: (632) 837-1359 / Telefax: (632) 839-0086</p>
            </div>
        </div>

        <div class="logbook-title">
            <h3>
                <?php 
                    if ($current_year) echo htmlspecialchars($current_year) . " ";
                    echo $current_category ? strtoupper(htmlspecialchars($current_category)) : "ALL ISSUANCES"; 
                ?>
            </h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Issuance / Project No.</th>
                    <th style="width: 15%;">Date Issued</th>
                    <th style="width: 45%;">Subject</th>
                    <th style="width: 10%;">Division</th>
               </tr>
            </thead>
            <tbody>
                <?php if (!empty($issuances)): ?>
                    <?php foreach ($issuances as $row): ?>
                    <tr>
                        <td style="text-align: center; font-size: 12px; color: #000;">
                            <?php 
                                $is_lib = (stripos($row['category'] ?? '', 'lib') !== false || stripos($row['category'] ?? '', 'line-item') !== false);
                                if ($is_lib && !empty($row['project_number'])) {
                                    echo htmlspecialchars($row['project_number']);
                                } else {
                                    echo htmlspecialchars($row['issuance_number']);
                                }
                            ?>
                        </td>
                        <td style="text-align: center; font-size: 12px; color: #000;"><?php echo date('m-d-Y', strtotime($row['date_issued'])); ?></td>
                        <td>
                            <?php 
                                if ($is_lib) {
                                    
                                    $desc = !empty($row['project_desc']) ? $row['project_desc'] : $row['subject'];
                                    echo "<strong style='font-size: 12px; color: #000;'>" . nl2br(htmlspecialchars($desc)) . "</strong><br>";
                                    
                                   
                                    echo "<div style='margin-top: 6px; font-size: 12px; color: #000; border-top: 1px dashed #ccc; padding-top: 4px;'>";
                                    
                                    $duration = htmlspecialchars(($row['start_month'] ?? '') . ' - ' . ($row['end_month'] ?? '') . ' ' . ($row['duration_year'] ?? ''));
                                    echo "<strong>Duration:</strong> " . $duration . " &nbsp;|&nbsp; ";
                                    
                                    echo "<strong>Action:</strong> " . htmlspecialchars($row['action_type'] ?? '') . " &nbsp;|&nbsp; ";
                                    
                                    $amount = number_format((float)($row['amount'] ?? 0), 2);
                                    echo "<strong>Budget: ₱" . $amount . "</strong>";
                                    
                                    echo "</div>";
                                } else {
                                    
                                    echo "<span style='font-size: 12px; color: #000;'>" . nl2br(htmlspecialchars($row['subject'])) . "</span>"; 
                                }
                            ?>
                        </td>
                        <td style="text-align: center; font-size: 12px; color: #000;"><?php echo htmlspecialchars($row['division'] ?? 'SEI'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center; padding: 20px; font-size: 12px; color: #000;">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="margin-top: 30px; text-align: right;">
            <p style="font-size: 10px; font-weight: bold; color: #000;">DOST-SEI LOGBOOK SYSTEM</p>
        </div>
    </div> 
</body>
</html>
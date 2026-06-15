<?php

function getIssuances($conn, $category = null, $search = null, $year = null, $sort = 'issuance_asc') {
  
    $sql = "SELECT i.*, 
                   l.project_number, 
                   l.project_desc, 
                   l.start_month, 
                   l.end_month, 
                   l.duration_year, 
                   l.action_type, 
                   l.amount 
            FROM issuance_tb i
            LEFT JOIN lib_details_tb l ON i.document_id = l.document_id 
            WHERE 1=1";
            
    $params = [];
    $types = "";

    if (!empty($category)) {
        $sql .= " AND i.category = ?";
        $params[] = $category;
        $types .= "s";
    }

    if (!empty($year)) {
        $sql .= " AND YEAR(i.date_issued) = ?";
        $params[] = $year;
        $types .= "s";
    }

    if (!empty($search)) {
       
        $sql .= " AND (i.subject LIKE ? OR i.document_id LIKE ? OR i.issuance_number LIKE ? OR l.project_number LIKE ? OR l.project_desc LIKE ?)";
        $searchTerm = "%" . $search . "%";
        $params[] = $searchTerm; 
        $params[] = $searchTerm; 
        $params[] = $searchTerm; 
        $params[] = $searchTerm;
        $params[] = $searchTerm; 
        $types .= "sssss";
    }

    if ($sort === 'newest') {
        $sql .= " ORDER BY i.created_at DESC, i.document_id DESC"; 
    } elseif ($sort === 'oldest') {
        $sql .= " ORDER BY i.created_at ASC, i.document_id ASC";  
    } elseif ($sort === 'id_desc') {
        $sql .= " ORDER BY i.document_id DESC";
    } elseif ($sort === 'id_asc') {
        $sql .= " ORDER BY i.document_id ASC";
    } elseif ($sort === 'issuance_asc') {
        $sql .= " ORDER BY i.issuance_number ASC"; 
    } else {
        $sql .= " ORDER BY i.issuance_number ASC"; 
    }
    
    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getAvailableYears($conn) {
    $sql = "SELECT DISTINCT YEAR(date_issued) as year 
            FROM issuance_tb 
            WHERE date_issued IS NOT NULL 
            ORDER BY year DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $years = [];
    while ($row = $result->fetch_assoc()) {
        $years[] = $row['year'];
    }
    return $years;
}


function generateDocumentID($conn, $year) {
    $prefix = "SEI-" . $year . "-";
    $stmt = $conn->prepare("SELECT document_id FROM issuance_tb WHERE document_id LIKE ? ORDER BY document_id DESC LIMIT 1");
    $searchPrefix = $prefix . '%';
    $stmt->bind_param("s", $searchPrefix);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $last_id = $row ? $row['document_id'] : null;

    if ($last_id) {
        $parts = explode('-', $last_id);
        $next_num = (int) end($parts) + 1;
    } else {
        $next_num = 1;
    }
    return $prefix . str_pad($next_num, 3, '0', STR_PAD_LEFT);
}


function generateIssuanceNumber($conn, $year) {
    $prefix = $year . "-";

    $stmt = $conn->prepare("SELECT issuance_number FROM issuance_tb WHERE issuance_number LIKE ? ORDER BY issuance_number DESC LIMIT 1");
    $searchPrefix = $prefix . '%';
    $stmt->bind_param("s", $searchPrefix);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $last_num = $row ? $row['issuance_number'] : null;

    if ($last_num) {
        $parts = explode('-', $last_num);
        
        $next_num = (isset($parts[1]) ? (int)$parts[1] : 0) + 1;
    } else {
        $next_num = 1;
    }

    return $prefix . str_pad($next_num, 3, '0', STR_PAD_LEFT);
}
?>
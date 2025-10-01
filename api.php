<?php
// Koneksi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'arsiparis_setwan';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Ambil data arsip
$stmt = $pdo->query("SELECT * FROM arsip ORDER BY created_at DESC");
$arsip_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung statistik
$total_arsip = $pdo->query("SELECT COUNT(*) FROM arsip")->fetchColumn();
$total_kategori = $pdo->query("SELECT COUNT(DISTINCT kategori) FROM arsip")->fetchColumn();
$diakses_bulan_ini = $pdo->query("SELECT COUNT(*) FROM arsip WHERE MONTH(created_at) = MONTH(CURRENT_DATE())")->fetchColumn();
$arsip_terbaru = $pdo->query("SELECT COUNT(*) FROM arsip WHERE DATE(created_at) = CURDATE()")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip - Arsiparis Setwan Tulungagung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3090FE;
            --secondary: #3090FE;
            --accent: #3090FE;
            --neutral: #8D9BAA;
            --light: #8BBAC0;
            --dark: #2D3748;
            --light-text: #ffffff;
            --card-bg: #ffffff;
            --section-bg: #F1F4F9;
            --border-color: #E2E8F0;
            --success: #48BB78;
            --warning: #ECC94B;
            --danger: #F56565;
            --info: #4299E1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--section-bg);
            color: var(--dark);
            display: flex;
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            color: var(--dark);
            padding: 0;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
        }

        .logo-container {
            padding: 25px 20px;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .logo i {
            font-size: 2.2rem;
            color: var(--primary);
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 600;
            text-align: left;
            color: var(--primary);
        }

        .logo-text p {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-top: 2px;
            text-align: left;
        }

        .nav-container {
            padding: 20px 0;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-links li {
            padding: 0;
            margin: 0;
            transition: all 0.3s ease;
        }

        .nav-links a {
            color: var(--dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1rem;
            padding: 16px 25px;
            transition: all 0.3s ease;
        }

        .nav-links li:hover {
            background-color: rgba(48, 144, 254, 0.05);
        }

        .nav-links li.active {
            background-color: rgba(48, 144, 254, 0.1);
            border-left: 4px solid var(--primary);
        }

        .nav-links i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            color: var(--primary);
        }

        .nav-footer {
            padding: 20px;
            margin-top: auto;
            border-top: 1px solid var(--border-color);
            font-size: 0.8rem;
            text-align: center;
            opacity: 0.7;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Header */
        .header {
            background-color: white;
            color: var(--dark);
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .header-left p {
            font-size: 0.9rem;
            opacity: 0.7;
            margin-top: 4px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-container {
            display: flex;
            align-items: center;
            background-color: var(--section-bg);
            border-radius: 30px;
            padding: 10px 20px;
            width: 320px;
            border: 1px solid var(--border-color);
        }

        .search-container input {
            background: transparent;
            border: none;
            color: var(--dark);
            width: 100%;
            padding: 5px;
            outline: none;
            font-size: 0.95rem;
        }

        .search-container input::placeholder {
            color: var(--neutral);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notifications {
            position: relative;
            cursor: pointer;
            background: var(--section-bg);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-badge {
            position: absolute;
            top: -3px;
            right: -3px;
            background-color: var(--danger);
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }

        .user-profile:hover {
            background-color: var(--section-bg);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #1E7FE0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            font-size: 1rem;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 500;
            font-size: 0.95rem;
        }

        .user-role {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        /* Content Area */
        .content {
            padding: 30px;
            flex: 1;
            overflow-y: auto;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary), #1E7FE0);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(48, 144, 254, 0.2);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .page-title h1 {
            font-size: 2rem;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .page-title p {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 500px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .page-header .icon {
            font-size: 5.5rem;
            opacity: 0.2;
            z-index: 1;
        }

        .btn {
            background-color: white;
            color: var(--primary);
            border: none;
            padding: 12px 28px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-secondary {
            background-color: var(--section-bg);
            color: var(--dark);
            border: 1px solid var(--border-color);
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        /* Archive Section */
        .archive-section {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-title {
            font-size: 1.35rem;
            color: var(--primary);
            font-weight: 600;
        }

        .section-actions {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Filter and Search */
        .filter-container {
            background-color: var(--section-bg);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid var(--border-color);
        }

        .filter-row {
            display: flex;
            gap: 20px;
            align-items: end;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 200px;
        }

        .filter-group label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--dark);
        }

        .filter-group select,
        .filter-group input {
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: white;
            color: var(--dark);
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            border-color: var(--primary);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--border-color);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background-color: rgba(48, 144, 254, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.4rem;
            margin: 0 auto 15px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--dark);
            opacity: 0.8;
        }

        /* Archive Grid/Table Toggle */
        .view-toggle {
            display: flex;
            background-color: var(--section-bg);
            border-radius: 8px;
            padding: 4px;
            border: 1px solid var(--border-color);
        }

        .view-toggle button {
            background: none;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--neutral);
        }

        .view-toggle button.active {
            background-color: var(--primary);
            color: white;
        }

        /* Archive Table */
        .archive-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .archive-table thead {
            background-color: var(--section-bg);
        }

        .archive-table th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }

        .archive-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }

        .archive-table tbody tr {
            transition: background-color 0.3s ease;
        }

        .archive-table tbody tr:hover {
            background-color: rgba(48, 144, 254, 0.02);
        }

        /* Archive Grid */
        .archive-grid {
            display: none;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        .archive-grid.active {
            display: grid;
        }

        .archive-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .archive-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), #1E7FE0);
        }

        .archive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.08);
        }

        .archive-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .file-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background-color: rgba(48, 144, 254, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.4rem;
        }

        .archive-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .archive-meta {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 15px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--neutral);
        }

        .meta-item i {
            width: 16px;
            text-align: center;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-active {
            background-color: rgba(72, 187, 120, 0.1);
            color: var(--success);
        }

        .status-draft {
            background-color: rgba(236, 201, 75, 0.1);
            color: var(--warning);
        }

        .status-archived {
            background-color: rgba(139, 186, 192, 0.1);
            color: var(--light);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .action-btn {
            background: none;
            border: 1px solid var(--border-color);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            color: var(--dark);
        }

        .action-btn:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .pagination button {
            background: none;
            border: 1px solid var(--border-color);
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--dark);
        }

        .pagination button:hover,
        .pagination button.active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Footer */
        .footer {
            background-color: var(--card-bg);
            color: var(--dark);
            padding: 25px 30px;
            border-top: 1px solid var(--border-color);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-links {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: var(--dark);
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .footer-links a:hover {
            color: var(--primary);
            opacity: 1;
        }

        .copyright {
            font-size: 0.9rem;
            opacity: 0.7;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: white;
            border-radius: 16px;
            padding: 30px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary);
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--neutral);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background-color: var(--section-bg);
            color: var(--danger);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: white;
            color: var(--dark);
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: var(--primary);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* File Upload Styles */
        .file-upload-container {
            position: relative;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            background-color: var(--section-bg);
        }

        .file-upload-container:hover {
            border-color: var(--primary);
            background-color: rgba(48, 144, 254, 0.05);
        }

        .file-upload-container input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            color: var(--neutral);
            font-size: 0.9rem;
        }

        .file-upload-label i {
            font-size: 2rem;
            color: var(--primary);
        }

        /* Scan Folder Container */
        .scan-folder-container {
            background-color: var(--section-bg);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .scan-folder-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .scan-folder-title {
            font-size: 1.2rem;
            color: var(--primary);
            font-weight: 600;
        }

        .scan-folder-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .scan-folder-dropzone {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
            background-color: white;
            cursor: pointer;
        }

        .scan-folder-dropzone:hover {
            border-color: var(--primary);
            background-color: rgba(48, 144, 254, 0.05);
        }

        .scan-folder-dropzone.active {
            border-color: var(--primary);
            background-color: rgba(48, 144, 254, 0.1);
        }

        .scan-folder-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .scan-folder-text {
            font-size: 1rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .scan-folder-subtext {
            font-size: 0.85rem;
            color: var(--neutral);
        }

        .scan-progress {
            margin-top: 20px;
            display: none;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: var(--border-color);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background-color: var(--primary);
            width: 0%;
            transition: width 0.3s ease;
        }

        .progress-text {
            font-size: 0.85rem;
            color: var(--neutral);
            text-align: center;
        }

        .scan-results {
            margin-top: 20px;
            display: none;
        }

        .scan-result-item {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .scan-result-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .scan-result-icon {
            font-size: 1.2rem;
            color: var(--primary);
        }

        .scan-result-name {
            font-weight: 500;
        }

        .scan-result-status {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 12px;
            background-color: rgba(72, 187, 120, 0.1);
            color: var(--success);
        }

        .scan-result-status.error {
            background-color: rgba(245, 101, 101, 0.1);
            color: var(--danger);
        }

        /* Grid layout for form on larger screens */
        @media (min-width: 768px) {
            #addArchiveForm {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }
            
            #addArchiveForm .form-group:nth-last-child(3),
            #addArchiveForm .form-group:nth-last-child(2),
            #addArchiveForm .form-group:nth-last-child(1) {
                grid-column: 1 / -1;
            }
        }

        /* Toggle button for mobile */
        .menu-toggle {
            display: none;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
            }
            
            .logo-text, .nav-links span {
                display: none;
            }
            
            .logo {
                justify-content: center;
            }
            
            .nav-links {
                align-items: center;
            }
            
            .nav-links a {
                justify-content: center;
                padding: 16px 0;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
            
            .archive-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
                padding: 15px;
            }
            
            .header-left, .header-right {
                width: 100%;
                justify-content: center;
            }
            
            .search-container {
                width: 100%;
                max-width: 400px;
            }
            
            .user-profile {
                padding: 8px;
            }
            
            .user-details {
                display: none;
            }
            
            .sidebar {
                width: 0;
                overflow: hidden;
                position: fixed;
                height: 100%;
                z-index: 1000;
            }
            
            .sidebar.active {
                width: 280px;
            }
            
            .page-header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
                padding: 20px;
            }
            
            .page-header .icon {
                font-size: 4rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: stretch;
                gap: 20px;
            }
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-group {
                min-width: auto;
            }
            
            .footer-content {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .footer-links {
                justify-content: center;
            }
            
            .menu-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background-color: var(--primary);
                color: white;
                width: 45px;
                height: 45px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 1.2rem;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            
            .content {
                padding: 20px;
            }
            
            .modal-content {
                padding: 20px;
                width: 95%;
            }
            
            .archive-table {
                font-size: 0.8rem;
            }
            
            .archive-table th,
            .archive-table td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-archive"></i>
                <div class="logo-text">
                    <h1>Arsiparis Setwan</h1>
                    <p>Tulungagung</p>
                </div>
            </div>
        </div>
        
        <div class="nav-container">
            <ul class="nav-links">
                <li>
                    <a href="Dashboard.php">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="Surat_Masuk.php">
                        <i class="fas fa-envelope"></i>
                        <span>Surat Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="Surat_Keluar.php">
                        <i class="fas fa-paper-plane"></i>
                        <span>Surat Keluar</span>
                    </a>
                </li>
                <li class="active">
                    <a href="Arsip.php">
                        <i class="fas fa-file-alt"></i>
                        <span>Arsip</span>
                    </a>
                </li>
                
                <li>
                    <a href="Profil_Pengaturan.php">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
                <li>
                    <a href="Laporan.php">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-footer">
            <p>Arsiparis Setwan Tulungagung © 2025</p>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <div class="header-left">
                <h2>Manajemen Arsip</h2>
                <p>Kelola dan organisir seluruh arsip digital dengan mudah</p>
            </div>
            <div class="header-right">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari arsip berdasarkan nama, kategori, atau tanggal..." id="globalSearch">
                </div>
                <div class="user-info">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-profile">
                        <div class="user-avatar">
                            <span>AD</span>
                        </div>
                        <div class="user-details">
                            <div class="user-name">Admin Dashboard</div>
                            <div class="user-role">Super Admin</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h1>Arsip Digital</h1>
                    <p>Kelola semua arsip digital Anda dengan sistem yang terintegrasi dan mudah digunakan. Fitur pencarian canggih membantu Anda menemukan dokumen dengan cepat.</p>
                    <button class="btn" onclick="openModal('addArchiveModal')">
                        <i class="fas fa-plus"></i> Tambah Arsip Baru
                    </button>
                </div>
                <div class="icon">
                    <i class="fas fa-folder-open"></i>
                </div>
            </div>

            <!-- Scan Folder Section -->
            <div class="scan-folder-container">
                <div class="scan-folder-header">
                    <h3 class="scan-folder-title">Scan Folder Otomatis</h3>
                    <button class="btn btn-primary btn-small" onclick="startFolderScan()">
                        <i class="fas fa-play"></i> Mulai Scan
                    </button>
                </div>
                <div class="scan-folder-content">
                    <div class="scan-folder-dropzone" id="scanFolderDropzone">
                        <div class="scan-folder-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div class="scan-folder-text">
                            Taruh folder di sini untuk di-scan
                        </div>
                        <div class="scan-folder-subtext">
                            Atau klik untuk memilih folder
                        </div>
                        <input type="file" id="folderInput" webkitdirectory multiple style="display: none;">
                    </div>
                    
                    <div class="scan-progress" id="scanProgress">
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                        <div class="progress-text" id="progressText">Mengidentifikasi dokumen...</div>
                    </div>
                    
                    <div class="scan-results" id="scanResults">
                        <h4>Hasil Scan</h4>
                        <div id="scanResultsList">
                            <!-- Results will be populated here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_arsip; ?></div>
                    <div class="stat-label">Total Arsip</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_kategori; ?></div>
                    <div class="stat-label">Kategori</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-value"><?php echo $diakses_bulan_ini; ?></div>
                    <div class="stat-label">Diakses Bulan Ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value"><?php echo $arsip_terbaru; ?></div>
                    <div class="stat-label">Arsip Terbaru</div>
                </div>
            </div>

            <!-- Archive Management Section -->
            <div class="archive-section">
                <div class="section-header">
                    <h3 class="section-title">Daftar Arsip</h3>
                    <div class="section-actions">
                        <div class="view-toggle">
                            <button class="active" onclick="toggleView('table')">
                                <i class="fas fa-list"></i>
                            </button>
                            <button onclick="toggleView('grid')">
                                <i class="fas fa-th-large"></i>
                            </button>
                        </div>
                        <button class="btn btn-secondary btn-small" onclick="exportArchive()">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <button class="btn btn-primary btn-small" onclick="openModal('addArchiveModal')">
                            <i class="fas fa-plus"></i> Tambah Arsip
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-container">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="categoryFilter">Kategori</label>
                            <select id="categoryFilter" onchange="applyFilters()">
                                <option value="">Semua Kategori</option>
                                <option value="keuangan">Keuangan</option>
                                <option value="administrasi">Administrasi</option>
                                <option value="kegiatan">Kegiatan</option>
                                <option value="kepegawaian">Kepegawaian</option>
                                <option value="surat-masuk">Surat Masuk</option>
                                <option value="surat-keluar">Surat Keluar</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="statusFilter">Status</label>
                            <select id="statusFilter" onchange="applyFilters()">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="draft">Draft</option>
                                <option value="arsip">Diarsipkan</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="dateFromFilter">Tanggal Dari</label>
                            <input type="date" id="dateFromFilter" onchange="applyFilters()">
                        </div>
                        <div class="filter-group">
                            <label for="dateToFilter">Tanggal Sampai</label>
                            <input type="date" id="dateToFilter" onchange="applyFilters()">
                        </div>
                        <div class="filter-group">
                            <button class="btn btn-secondary btn-small" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table View -->
                <div id="tableView">
                    <table class="archive-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                </th>
                                <th>Nama Arsip</th>
                                <th>Kategori</th>
                                <th>Tanggal Upload</th>
                                <th>Ukuran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="archiveTableBody">
                            <?php foreach($arsip_data as $arsip): ?>
                            <tr data-category="<?php echo $arsip['kategori']; ?>" data-status="<?php echo $arsip['status']; ?>" data-date="<?php echo $arsip['tanggal_surat']; ?>">
                                <td><input type="checkbox" class="archive-checkbox"></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-file-pdf" style="color: #F56565; font-size: 1.2rem;"></i>
                                        <div>
                                            <div style="font-weight: 500;"><?php echo htmlspecialchars($arsip['perihal']); ?></div>
                                            <div style="font-size: 0.8rem; opacity: 0.7;"><?php echo htmlspecialchars($arsip['nomor_surat']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="status-badge" style="background: rgba(72, 187, 120, 0.1); color: #48BB78;"><?php echo ucfirst($arsip['kategori']); ?></span></td>
                                <td><?php echo date('d M Y', strtotime($arsip['tanggal_surat'])); ?></td>
                                <td><?php echo $arsip['file_surat'] ? 'Ada File' : 'Tidak Ada'; ?></td>
                                <td><span class="status-badge status-<?php echo $arsip['status']; ?>"><?php echo ucfirst($arsip['status']); ?></span></td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <button class="action-btn" onclick="viewArchive(<?php echo $arsip['id']; ?>)" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn" onclick="downloadArchive(<?php echo $arsip['id']; ?>)" title="Download">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="action-btn" onclick="editArchive(<?php echo $arsip['id']; ?>)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn" onclick="deleteArchive(<?php echo $arsip['id']; ?>)" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Grid View -->
                <div class="archive-grid" id="gridView">
                    <?php foreach($arsip_data as $arsip): ?>
                    <div class="archive-card" data-category="<?php echo $arsip['kategori']; ?>" data-status="<?php echo $arsip['status']; ?>" data-date="<?php echo $arsip['tanggal_surat']; ?>">
                        <div class="archive-card-header">
                            <div class="file-icon">
                                <i class="fas fa-file-pdf" style="color: #F56565;"></i>
                            </div>
                            <input type="checkbox" class="archive-checkbox">
                        </div>
                        <h4 class="archive-title"><?php echo htmlspecialchars($arsip['perihal']); ?></h4>
                        <div class="archive-meta">
                            <div class="meta-item">
                                <i class="fas fa-folder"></i>
                                <span><?php echo ucfirst($arsip['kategori']); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span><?php echo date('d M Y', strtotime($arsip['tanggal_surat'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-file"></i>
                                <span><?php echo $arsip['file_surat'] ? 'Ada File' : 'Tidak Ada'; ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="status-badge status-<?php echo $arsip['status']; ?>"><?php echo ucfirst($arsip['status']); ?></span>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button class="action-btn" onclick="viewArchive(<?php echo $arsip['id']; ?>)">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                            <button class="action-btn" onclick="downloadArchive(<?php echo $arsip['id']; ?>)">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button class="action-btn" onclick="editArchive(<?php echo $arsip['id']; ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <button onclick="changePage('prev')" id="prevBtn">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </button>
                    <button class="active" onclick="changePage(1)">1</button>
                    <button onclick="changePage(2)">2</button>
                    <button onclick="changePage(3)">3</button>
                    <span>...</span>
                    <button onclick="changePage(10)">10</button>
                    <button onclick="changePage('next')" id="nextBtn">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="#">About Us</a>
                    <a href="#">Help And FAQ</a>
                    <a href="#">Contact Us</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms And Conditions</a>
                </div>
                <div class="copyright">
                    All Rights Reserved | © Arsiparis_setwan - 2025
                </div>
            </div>
        </div>
    </div>

    <!-- Add Archive Modal -->
    <div class="modal" id="addArchiveModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Arsip Baru</h3>
                <button class="close-btn" onclick="closeModal('addArchiveModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addArchiveForm" action="process_arsip.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="suratDari">Surat Dari</label>
                    <input type="text" id="suratDari" name="suratDari" required placeholder="Enter Surat Dari">
                </div>
                
                <div class="form-group">
                    <label for="tanggalSurat">Tanggal Surat</label>
                    <input type="date" id="tanggalSurat" name="tanggalSurat" required>
                </div>
                
                <div class="form-group">
                    <label for="nomorSurat">Nomor Surat</label>
                    <input type="text" id="nomorSurat" name="nomorSurat" required placeholder="Enter Nomor Surat">
                </div>
                
                <div class="form-group">
                    <label for="perihal">Perihal</label>
                    <input type="text" id="perihal" name="perihal" required placeholder="Enter Perihal">
                </div>
                
                <div class="form-group">
                    <label for="diterimaTanggal">Diterima Tanggal</label>
                    <input type="date" id="diterimaTanggal" name="diterimaTanggal" required>
                </div>
                
                <div class="form-group">
                    <label for="kepada">Kepada</label>
                    <select id="kepada" name="kepada" required>
                        <option value="">Select a value ...</option>
                        <option value="sekretaris">Sekretaris</option>
                        <option value="kabag">Kepala Bagian</option>
                        <option value="kasubag">Kepala Sub Bagian</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="nomorAgenda">Nomor Agenda</label>
                    <input type="text" id="nomorAgenda" name="nomorAgenda" required placeholder="Enter Nomor Agenda">
                </div>
                
                <div class="form-group">
                    <label for="fileSurat">File Surat</label>
                    <div class="file-upload-container">
                        <input type="file" id="fileSurat" name="fileSurat" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.png">
                        <div class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Choose files or drag and drop files to upload</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="disposisi">Disposisi</label>
                    <select id="disposisi" name="disposisi" required>
                        <option value="">Select a value ...</option>
                        <option value="tindak-lanjut">Tindak Lanjut</option>
                        <option value="arsip">Arsip</option>
                        <option value="koodinasi">Koordinasi</option>
                        <option value="pelaksanaan">Pelaksanaan</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="fileDisposisi">File Disposisi</label>
                    <div class="file-upload-container">
                        <input type="file" id="fileDisposisi" name="fileDisposisi" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.png">
                        <div class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Choose files or drag and drop files to upload</span>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 25px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addArchiveModal')">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Arsip
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Archive Modal -->
    <div class="modal" id="viewArchiveModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Arsip</h3>
                <button class="close-btn" onclick="closeModal('viewArchiveModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="archiveDetails">
                <!-- Archive details will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        let currentView = 'table';
        let currentPage = 1;
        let isScanning = false;

        // Toggle sidebar on mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Toggle view between table and grid
        function toggleView(view) {
            currentView = view;
            const tableView = document.getElementById('tableView');
            const gridView = document.getElementById('gridView');
            const buttons = document.querySelectorAll('.view-toggle button');
            
            buttons.forEach(btn => btn.classList.remove('active'));
            
            if (view === 'table') {
                tableView.style.display = 'block';
                gridView.classList.remove('active');
                buttons[0].classList.add('active');
            } else {
                tableView.style.display = 'none';
                gridView.classList.add('active');
                buttons[1].classList.add('active');
            }
        }

        // Folder scan functionality
        const scanFolderDropzone = document.getElementById('scanFolderDropzone');
        const folderInput = document.getElementById('folderInput');
        const scanProgress = document.getElementById('scanProgress');
        const progressFill = document.getElementById('progressFill');
        const progressText = document.getElementById('progressText');
        const scanResults = document.getElementById('scanResults');
        const scanResultsList = document.getElementById('scanResultsList');

        // Click to select folder
        scanFolderDropzone.addEventListener('click', function() {
            folderInput.click();
        });

        // Drag and drop functionality
        scanFolderDropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('active');
        });

        scanFolderDropzone.addEventListener('dragleave', function() {
            this.classList.remove('active');
        });

        scanFolderDropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('active');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                startScanProcess(files);
            }
        });

        // Folder input change
        folderInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                startScanProcess(this.files);
            }
        });

        // Start folder scan
        function startFolderScan() {
            folderInput.click();
        }

        // Simulate AI image-to-text scanning process
        function startScanProcess(files) {
            if (isScanning) return;
            
            isScanning = true;
            scanProgress.style.display = 'block';
            scanResults.style.display = 'none';
            scanResultsList.innerHTML = '';
            
            let progress = 0;
            const totalFiles = Math.min(files.length, 10); // Limit for demo
            
            // Simulate scanning progress
            const interval = setInterval(() => {
                progress += 10;
                progressFill.style.width = `${progress}%`;
                
                if (progress <= 30) {
                    progressText.textContent = 'Mengidentifikasi dokumen...';
                } else if (progress <= 60) {
                    progressText.textContent = 'Memproses teks dengan AI...';
                } else if (progress <= 90) {
                    progressText.textContent = 'Menganalisis konten...';
                } else {
                    progressText.textContent = 'Menyelesaikan...';
                }
                
                if (progress >= 100) {
                    clearInterval(interval);
                    simulateScanResults(totalFiles);
                    isScanning = false;
                }
            }, 300);
        }

        // Simulate scan results with AI-extracted data
        function simulateScanResults(fileCount) {
            scanProgress.style.display = 'none';
            scanResults.style.display = 'block';
            
            // Sample data that AI would extract
            const sampleData = [
                {
                    name: 'Surat Edaran No. 001',
                    suratDari: 'Kementerian Dalam Negeri',
                    tanggalSurat: '2024-01-15',
                    nomorSurat: '001/KD/IX/2024',
                    perihal: 'Petunjuk Teknis Pelaksanaan Program',
                    diterimaTanggal: '2024-01-18',
                    kepada: 'sekretaris',
                    nomorAgenda: 'AG-2024-001',
                    disposisi: 'tindak-lanjut'
                },
                {
                    name: 'Laporan Keuangan Triwulan I',
                    suratDari: 'Badan Pengelola Keuangan',
                    tanggalSurat: '2024-03-31',
                    nomorSurat: 'BPK/045/III/2024',
                    perihal: 'Laporan Realisasi Anggaran Triwulan I',
                    diterimaTanggal: '2024-04-05',
                    kepada: 'kabag',
                    nomorAgenda: 'AG-2024-045',
                    disposisi: 'arsip'
                },
                {
                    name: 'Undangan Rapat Koordinasi',
                    suratDari: 'Sekretariat Daerah',
                    tanggalSurat: '2024-02-10',
                    nomorSurat: '005/SEKDA/II/2024',
                    perihal: 'Undangan Rapat Koordinasi Bulanan',
                    diterimaTanggal: '2024-02-12',
                    kepada: 'kasubag',
                    nomorAgenda: 'AG-2024-028',
                    disposisi: 'koodinasi'
                }
            ];
            
            // Display results
            for (let i = 0; i < Math.min(fileCount, sampleData.length); i++) {
                const data = sampleData[i];
                const resultItem = document.createElement('div');
                resultItem.className = 'scan-result-item';
                resultItem.innerHTML = `
                    <div class="scan-result-info">
                        <div class="scan-result-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <div class="scan-result-name">${data.name}</div>
                            <div style="font-size: 0.8rem; color: var(--neutral);">${data.perihal}</div>
                        </div>
                    </div>
                    <div class="scan-result-status">Berhasil dipindai</div>
                `;
                
                // Add click to auto-fill form
                resultItem.addEventListener('click', function() {
                    autoFillForm(data);
                });
                
                scanResultsList.appendChild(resultItem);
            }
            
            // Show notification
            showNotification(`${Math.min(fileCount, sampleData.length)} dokumen berhasil dipindai`, 'success');
        }

        // Auto-fill form with scanned data
        function autoFillForm(data) {
            document.getElementById('suratDari').value = data.suratDari;
            document.getElementById('tanggalSurat').value = data.tanggalSurat;
            document.getElementById('nomorSurat').value = data.nomorSurat;
            document.getElementById('perihal').value = data.perihal;
            document.getElementById('diterimaTanggal').value = data.diterimaTanggal;
            document.getElementById('kepada').value = data.kepada;
            document.getElementById('nomorAgenda').value = data.nomorAgenda;
            document.getElementById('disposisi').value = data.disposisi;
            
            // Open the modal
            openModal('addArchiveModal');
            
            // Show notification
            showNotification('Form berhasil diisi otomatis dengan data hasil scan', 'success');
        }

        // Apply filters
        function applyFilters() {
            const category = document.getElementById('categoryFilter').value;
            const status = document.getElementById('statusFilter').value;
            const dateFrom = document.getElementById('dateFromFilter').value;
            const dateTo = document.getElementById('dateToFilter').value;
            
            const tableRows = document.querySelectorAll('#archiveTableBody tr');
            const gridCards = document.querySelectorAll('.archive-grid .archive-card');
            
            // Filter table rows
            tableRows.forEach(row => {
                const rowCategory = row.getAttribute('data-category');
                const rowStatus = row.getAttribute('data-status');
                const rowDate = row.getAttribute('data-date');
                
                let show = true;
                
                if (category && rowCategory !== category) show = false;
                if (status && rowStatus !== status) show = false;
                if (dateFrom && rowDate < dateFrom) show = false;
                if (dateTo && rowDate > dateTo) show = false;
                
                row.style.display = show ? '' : 'none';
            });
            
            // Filter grid cards
            gridCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                const cardStatus = card.getAttribute('data-status');
                const cardDate = card.getAttribute('data-date');
                
                let show = true;
                
                if (category && cardCategory !== category) show = false;
                if (status && cardStatus !== status) show = false;
                if (dateFrom && cardDate < dateFrom) show = false;
                if (dateTo && cardDate > dateTo) show = false;
                
                card.style.display = show ? '' : 'none';
            });
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('categoryFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('dateFromFilter').value = '';
            document.getElementById('dateToFilter').value = '';
            document.getElementById('globalSearch').value = '';
            
            // Show all items
            const tableRows = document.querySelectorAll('#archiveTableBody tr');
            const gridCards = document.querySelectorAll('.archive-grid .archive-card');
            
            tableRows.forEach(row => row.style.display = '');
            gridCards.forEach(card => card.style.display = '');
        }

        // Global search functionality
        document.getElementById('globalSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll('#archiveTableBody tr');
            const gridCards = document.querySelectorAll('.archive-grid .archive-card');
            
            // Search in table
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Search in grid
            gridCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Select all functionality
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.archive-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        // Archive actions
        function viewArchive(id) {
            // Fetch archive details from server
            fetch(`get_archive.php?id=${id}`)
                .then(response => response.json())
                .then(archive => {
                    document.getElementById('archiveDetails').innerHTML = `
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding: 20px; background: var(--section-bg); border-radius: 12px;">
                            <div class="file-icon" style="width: 60px; height: 60px; font-size: 1.8rem;">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 5px; color: var(--primary);">${archive.perihal}</h4>
                                <p style="margin: 0; color: var(--neutral); font-size: 0.9rem;">${archive.nomor_surat}</p>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
                            <div>
                                <label style="font-weight: 500; color: var(--dark); display: block; margin-bottom: 5px;">Kategori:</label>
                                <span class="status-badge" style="background: rgba(72, 187, 120, 0.1); color: #48BB78;">${archive.kategori}</span>
                            </div>
                            <div>
                                <label style="font-weight: 500; color: var(--dark); display: block; margin-bottom: 5px;">Status:</label>
                                <span class="status-badge status-${archive.status}">${archive.status}</span>
                            </div>
                            <div>
                                <label style="font-weight: 500; color: var(--dark); display: block; margin-bottom: 5px;">Tanggal Upload:</label>
                                <span>${new Date(archive.tanggal_surat).toLocaleDateString('id-ID')}</span>
                            </div>
                            <div>
                                <label style="font-weight: 500; color: var(--dark); display: block; margin-bottom: 5px;">Surat Dari:</label>
                                <span>${archive.surat_dari}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                            <button class="btn btn-secondary" onclick="downloadArchive(${id})">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button class="btn btn-primary" onclick="editArchive(${id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    `;
                    openModal('viewArchiveModal');
                })
                .catch(error => {
                    showNotification('Gagal memuat detail arsip', 'error');
                });
        }

        function downloadArchive(id) {
            // Simulate download
            window.open(`download.php?id=${id}`, '_blank');
            showNotification('File berhasil didownload!', 'success');
        }

        function editArchive(id) {
            // Close view modal if open
            closeModal('viewArchiveModal');
            // Open edit form (you can populate with existing data)
            openModal('addArchiveModal');
            // Change modal title for editing
            document.querySelector('#addArchiveModal .modal-title').textContent = 'Edit Arsip';
            
            // Fetch existing data and populate form
            fetch(`get_archive.php?id=${id}`)
                .then(response => response.json())
                .then(archive => {
                    document.getElementById('suratDari').value = archive.surat_dari;
                    document.getElementById('tanggalSurat').value = archive.tanggal_surat;
                    document.getElementById('nomorSurat').value = archive.nomor_surat;
                    document.getElementById('perihal').value = archive.perihal;
                    document.getElementById('diterimaTanggal').value = archive.diterima_tanggal;
                    document.getElementById('kepada').value = archive.kepada;
                    document.getElementById('nomorAgenda').value = archive.nomor_agenda;
                    document.getElementById('disposisi').value = archive.disposisi;
                });
        }

        function deleteArchive(id) {
            if (confirm('Apakah Anda yakin ingin menghapus arsip ini?')) {
                fetch(`delete_archive.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Arsip berhasil dihapus!', 'success');
                        // Refresh the page to update the list
                        location.reload();
                    } else {
                        showNotification('Gagal menghapus arsip', 'error');
                    }
                })
                .catch(error => {
                    showNotification('Terjadi kesalahan saat menghapus arsip', 'error');
                });
            }
        }

        function exportArchive() {
            showNotification('Export arsip sedang diproses...', 'info');
        }

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
            
            // Reset form if it's add archive modal
            if (modalId === 'addArchiveModal') {
                document.getElementById('addArchiveForm').reset();
                document.querySelector('#addArchiveModal .modal-title').textContent = 'Tambah Arsip Baru';
            }
        }

        // Enhanced file upload functionality
        document.querySelectorAll('.file-upload-container input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const container = this.closest('.file-upload-container');
                const label = container.querySelector('.file-upload-label');
                const files = this.files;
                
                if (files.length > 0) {
                    const fileName = files[0].name;
                    const fileSize = (files[0].size / 1024 / 1024).toFixed(2); // Convert to MB
                    
                    label.innerHTML = `
                        <i class="fas fa-file" style="color: var(--success);"></i>
                        <div>
                            <div style="font-weight: 500; color: var(--dark);">${fileName}</div>
                            <div style="font-size: 0.8rem; color: var(--neutral);">${fileSize} MB</div>
                        </div>
                    `;
                    container.style.borderColor = 'var(--success)';
                    container.style.backgroundColor = 'rgba(72, 187, 120, 0.05)';
                } else {
                    label.innerHTML = `
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Choose files or drag and drop files to upload</span>
                    `;
                    container.style.borderColor = 'var(--border-color)';
                    container.style.backgroundColor = 'var(--section-bg)';
                }
            });
        });

        // Drag and drop functionality for file upload
        document.querySelectorAll('.file-upload-container').forEach(container => {
            container.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.style.borderColor = 'var(--primary)';
                this.style.backgroundColor = 'rgba(48, 144, 254, 0.1)';
            });
            
            container.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.style.borderColor = 'var(--border-color)';
                this.style.backgroundColor = 'var(--section-bg)';
            });
            
            container.addEventListener('drop', function(e) {
                e.preventDefault();
                const input = this.querySelector('input[type="file"]');
                input.files = e.dataTransfer.files;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            });
        });

        // Pagination
        function changePage(page) {
            const buttons = document.querySelectorAll('.pagination button');
            
            if (page === 'prev' && currentPage > 1) {
                currentPage--;
            } else if (page === 'next' && currentPage < 10) {
                currentPage++;
            } else if (typeof page === 'number') {
                currentPage = page;
            }
            
            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            if (typeof page === 'number') {
                buttons[page].classList.add('active');
            }
            
            // Update button states
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === 10;
            
            // Simulate loading new data
            showNotification(`Memuat halaman ${currentPage}...`, 'info');
        }

        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : 'var(--info)'};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                max-width: 300px;
                font-size: 0.9rem;
            `;
            
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (e.target === modal) {
                    closeModal(modal.id);
                }
            });
        });

        // Animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
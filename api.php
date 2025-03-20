<?php
//header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'bd_scolaire2';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Connexion échouée: ' . $e->getMessage()]);
    exit;
}

// Récupérer la liste des produits
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id'])) {
    $stmt = $pdo->query('SELECT * FROM produits');
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($produits);
}

// Récupérer les détails d'un produit
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        echo json_encode(['error' => 'ID invalide']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT p.*, d.description, d.caracteristiques 
                           FROM produits p 
                           JOIN details_produits d ON p.id = d.produit_id 
                           WHERE p.id = :id');
    $stmt->execute(['id' => $id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produit) {
        echo json_encode($produit);
    } else {
        echo json_encode(['error' => 'Produit non trouvé']);
    }
}
?>
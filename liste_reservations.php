<?php
$conn = new mysqli("localhost", "root", "", "event");
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$sql = "SELECT nom, prenom, mail, faculte, telephone FROM reservations";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Réservations</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 40px 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 90%;
            max-width: 1000px;
            margin: auto;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        td {
            color: #555;
        }
    </style>
</head>
<body>

<h2>Liste des Réservations</h2>

<?php
if ($result && $result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Faculté</th><th>Téléphone</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["nom"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["prenom"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["mail"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["faculte"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["telephone"]) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p style='text-align:center;'>Aucune réservation trouvée.</p>";
}

$conn->close();
?>

</body>
</html>

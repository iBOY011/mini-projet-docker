<?php
if (isset($_POST['list_students'])) {
    $username = "root";
    $password = "root";
    $url = "http://api:5000/supmit/api/v1.0/get_student_ages";
    $options = [
        'http' => [
            'header'  => "Authorization: Basic " . base64_encode("$username:$password")
        ]
    ];
    $context  = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    if ($response !== false) {
        $data = json_decode($response, true);
    } else {
        $data = null;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
</head>
<body>
    <h1>SUPMIT - Liste des étudiants</h1>
    <form method="post">
        <button type="submit" name="list_students">List Students</button>
    </form>

    <?php if (isset($data) && isset($data['student_ages'])): ?>
        <h2>Résultats :</h2>
        <ul>
            <?php foreach ($data['student_ages'] as $nom => $age): ?>
                <li><?= htmlspecialchars($nom) ?> : <?= htmlspecialchars($age) ?> ans</li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (isset($_POST['list_students'])): ?>
        <p>Impossible de récupérer la liste des étudiants (API non joignable ou credentials incorrects).</p>
    <?php endif; ?>
</body>
</html>

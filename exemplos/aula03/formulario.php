<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário</title>
</head>
<body>
    <form action="recebe_dados.php" method="POST">
        <p>Nome: <input type="text" name="nome" id=""> </p>
        <p>Email: <input type="email" name="email" id=""> </p>
        <p>Idade: <input type="number" name="idade" id=""> </p>
        <input type="submit" value="ENVIAR">
    </form>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Payment Failed</h4>
            <p>We're sorry, but your payment could not be processed. Please try again or contact support.</p>
            <hr>
            <p class="mb-0">Error: {{ $error }}</p>
        </div>
    </div>
</body>
</html>
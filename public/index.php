<!doctype html>

<head>
    <title>Test form</title>

    <style>
        label { display: inline-block; width: 220px; }
    </style>
</head>

<body>
    <form method="post" action="validate.php">
    <fieldset>
            <legend>Test Params</legend>
                <p>
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </p>

                <p>
                    <label for="birthdate">Birth Date</label>
                    <input type="text" id="birthdate" name="birthdate" required>
                </p>
                
                <p>
                    <label for="registration">Registration Number</label>
                    <input type="text" id="registration" name="registration" required>
                </p>
                
                <p>
                    <input type="submit" value="Validate">
                </p>
        </fieldset>
    </form>
</body>

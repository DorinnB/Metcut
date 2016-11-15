<!DOCTYPE html>
<html>
<head>
<title>Envoi d'un formulaire en Ajax avec jQuery</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {

    $('#login').click(function() {

        $.ajax({
            type: "POST",
            url: 'profile.php',
            data: {
                username: $("#username").val(),
                password: $("#password").val()
            },
            success: function(data)
            {
                if (data === 'Correct') {
                    window.location.replace('admin/admin.php');
                }
                else {
                    alert(data);
                }
            }
        });

    });

});
</script>
</head>

<body>
<form>

    <label for="username">Username</label>

					<SELECT id="username" name="technicien"><option value="0">-</option>
							<option value="19">ALX</option>
								<option value="17">BDI</option>
								<option value="16">CGA</option>
								<option value="18">HMA</option>
								<option value="21">JGA</option>
								<option value="11">MGG</option>
								<option value="15">PGO</option>
								<option value="3">PHL</option>
								<option value="9">PQF</option>
								<option value="20">TEO</option>
								<option value="4">THO</option>
								</select>
								
    <label for="password">Password</label>
    <input type="text" id="password" placeholder="Password" />

</form>

<button id="login">Login</button>
</body>

</html>
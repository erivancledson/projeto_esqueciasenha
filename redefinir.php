<?php
require 'config.php';
//verificase o token existe e se foi usado ou não
if(!empty($_GET['token'])) {
	$token = $_GET['token'];

	$sql = "SELECT * FROM usuarios_token WHERE hash = :hash AND used = 0 AND expirado_em > NOW()";
	$sql = $pdo->prepare($sql);
	$sql->bindValue(":hash", $token);
	$sql->execute();
     //se existe um token nessas condições
	if($sql->rowCount() > 0) {
		$sql = $sql->fetch();
		$id = $sql['id_usuario'];
       //se a senha for digitada
		if(!empty($_POST['senha'])) {
			$senha = $_POST['senha'];
             //atualiza a senha na tabela do usuario
			$sql = "UPDATE usuarios SET senha = :senha WHERE id = :id";
			$sql = $pdo->prepare($sql);
			$sql->bindValue(":senha", md5($senha));
			$sql->bindValue(":id", $id);
			$sql->execute();
             //atualiza para usado o toekn
			$sql = "UPDATE usuarios_token SET used = 1 WHERE hash = :hash";
			$sql = $pdo->prepare($sql);
			$sql->bindValue(":hash", $token);
			$sql->execute();

			echo "Senha alterada com sucesso!";
			exit;
		}

		?>
		<form method="POST">
			Digite a nova senha:<br/>
			<input type="password" name="senha" /><br/>
			<input type="submit" value="Mudar senha" />
		</form>
		<?php



	} else {
		echo "Token inválido ou usado!";
		exit;
	}
}
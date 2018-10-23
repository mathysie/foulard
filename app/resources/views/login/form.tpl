{extends 'base.tpl'}

{block title}Inloggen vereist{/block}

{block breadcrumbs}
<li class="breadcrumb-item active" aria-current="page">Inlogformulier</li>
{/block}

{block pagetitle}<h1>Inlog vereist</h1>{/block}

{block content append}
<div class="row">
	<div class="col-lg-6">
		<form method="post">
			<div class="form-group">
				<label for="gebruikersnaam">Gebruikersnaam</label>
				<input type="text" class="form-control" id="gebruikersnaam" name="gebruikersnaam" placeholder="gebruikersnaam">
			</div>
			<div class="form-group">
				<label for="wachtwoord">Wachtwoord</label>
				<input type="password" class="form-control" id="wachtwoord" name="wachtwoord" placeholder="wachtwoord">
			</div>
			<button class="btn btn-primary" type="submit">Inloggen</button>
		</form>
	</div>
</div>
{/block}
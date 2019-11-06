<?php
require_once 'header.php';

use core\controller\Eventos;
use core\controller\Atividades;
use core\sistema\Autenticacao;
use core\sistema\Footer;
use core\sistema\Util;
use core\controller\Tematicas;
use core\controller\Eventos_Tipos;

$evento_id = isset($_GET['evento_id']) ? $_GET['evento_id'] : null;

$eventos = new Eventos();
$atividades = new Atividades();
$tematicas = new Tematicas();
$tipos = new Eventos_Tipos();

$dados2 = [];
$evento = "";
$atividade = "";
$lista_tipos = "";

$evento = $eventos->listarEvento($evento_id);
$atividade = $atividades->listarAtividades($evento_id);
$lista_tematicas = $tematicas->listar($evento_id);
$lista_tipos = $tipos->listarEventosTipos($evento_id);

(strtotime(date('Y/m/d')) > strtotime($evento->evento_termino)) ? $d = "disabled" : $d = "";
(strtotime(date('Y/m/d')) < strtotime($evento->evento_termino)) ? $verificacaoGerarCeritificado = "disabled": $verificacaoGerarCeritificado = "";

if (!Autenticacao::usuarioAdministrador() && Autenticacao::verificarLogin()) {
	$dados_eventos = [];
    $dados_eventos['busca']['me'] = Autenticacao::getCookieUsuario();
    $dados2 = $eventos->listarEventos($dados_eventos); //eventos que o usuario se inscreveu
}

?>



<main role="main">
<!--	<div class="jumbotron mt-n5" style="height: 250px; border-radius:0px; background:url(assets/imagens/grande2.jpg) no-repeat 0 0"></div>-->

	<div class="container mt-5">
		<div class="card shadow-sm mb-4 p-4">
			<h1 class="display-5 text-center font-weight-bold mb-4"><?= $evento->nome ?></h1>
			<div class="row">
				<div class="col-md-4 align-self-center font-weight-bold text-primary">
					<div class="row">
						<div class="col-md-1 offset-md-3">
							<i class="fas fa-calendar-check"></i>
						</div>
						<div class="col-md-8">
							<?= Util::formataDataExtenso($evento->evento_inicio) ?>
						</div>
					</div>

					<div class="row mt-2">
						<div class="col-md-1 offset-md-3">
							<i class="fas fa-calendar-times"></i>
						</div>
						<div class="col-md-8">
							<?= Util::formataDataExtenso($evento->evento_termino) ?>
						</div>
					</div>

					<div class="row mt-2">
						<div class="col-md-1 offset-md-3">
							<i class="fas fa-map-marker-alt"></i>
						</div>
						<div class="col-md-8">
							<?= $evento->local ?>
						</div>
					</div>
				</div>

				<div class="col-md-8">
					<div class="row justify-align-center ">
						<div class="col-md-10 offset-md-1">
							<p class="text-justify">
								<?= $evento->descricao ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card shadow-sm mb-4 p-4">
			<div class="row">
				<div class="col-md-6 font-weight-bold text-center">
					<h3 class="mb-4">Inscrições</h3>

					<div class="row text-primary">
						<div class="col-md-12">
							<i class="fas fa-calendar-check mr-2"></i>
							<?= Util::formataDataExtenso($evento->data_inicio) ?>
						</div>
					</div>

					<div class="row mt-2 text-primary">
						<div class="col-md-12">
							<i class="fas fa-calendar-times mr-2"></i>
							<?php
								//colocar a data de prorrogação caso a data atual seja igual ou maior

								if (strtotime(date('Y/m/d')) >= strtotime($evento->data_termino)) {
									echo Util::formataDataExtenso($evento->data_prorrogacao);
								} else {
									echo Util::formataDataExtenso($evento->data_termino);
								}
							?>
						</div>
					</div>

					<div class="row mt-3" id="div1">
						<div class="col-md-10 offset-1 align-self-center align-text-middle">
							<!-- colocar badge dos temas do evento -->
							
							<?php 
							$cores = ['primary', 'secondary', 'success', 'danger', 'warning', 'dark'];
							$i = 0;
							foreach ($lista_tematicas as $key => $tematica) {
							?>
								<span class="badge badge-<?= $cores[$i++] ?>"> <?= $tematica->descricao ?> </span>
							<?php	
								if ($i > 5) $i = 0;
							}
							?>

							<!-- <span class="badge badge-pill badge-secondary">Agro</span>
							<span class="badge badge-pill badge-success">Bio</span>
							<span class="badge badge-pill badge-danger">Quimica</span>
							<span class="badge badge-pill badge-warning">Zoo</span> -->
							<!--  -->
						</div>
					</div>

					<div class="row mt-2">
						<div class="col-md-12">
							<?php
								$cont = 0;

								if (isset($dados2['lista_eventos']) && count((array)$dados2['lista_eventos'][0]) > 0) {
									foreach ($dados2['lista_eventos'] as $j => $evento2) {
										if ($evento->evento_id == $evento2->evento_id) $cont++;
									}
								}

								if ($cont == 1) {
									$a = "disabled";
									$b = "";
								} else {
									$a = "";
									$b = "disabled";
								}
							?>
							<p><small class="text-muted">Inscrições apenas pelo site.</small></p>
							<a href="atividades.php?evento_id=<?= $evento->evento_id ?>" class="btn btn-lg btn-outline-dark <?= $a ?> <?= $d ?>">Inscrever-se</a>
						</div>
					</div>
				</div>

				<div class="col-md-6 font-weight-bold text-center">
					<h3 class="mb-4">Submissões</h3>

					<div class="row text-primary">
						<div class="col-md-12">
							<i class="fas fa-calendar-check mr-2"></i>
							<?= $evento->data_inicio_sub != NULL ? Util::formataDataExtenso($evento->data_inicio_sub) : "" ?>
						</div>
					</div>

					<div class="row mt-2 text-primary">
						<div class="col-md-12">
							<i class="fas fa-calendar-times mr-2"></i>
							<?= $evento->data_termino_sub != NULL ? Util::formataDataExtenso($evento->data_termino_sub) : "" ?>
						</div>
					</div>

					<div class="row mt-3" id="div2">
						<div class="col-md-10 offset-1 align-self-center align-text-middle">
							
							<?php 
							$cores = ['primary', 'secondary', 'success', 'danger', 'warning', 'dark'];
							$i = 0;

							if ($lista_tipos != "") {							
								foreach ($lista_tipos as $key => $tipo) {
							?>
									<span class="badge badge-<?= $cores[$i++] ?>"> <?= $tipo->descricao ?> </span>
							<?php	
								if ($i > 5) $i = 0;
								}
							}
							?>

						</div>
					</div>

					<div class="row mt-2">
						<div class="col-md-12">
							<p><small class="text-muted">Submissões apenas pelo site.</small></p>
							<a href="#" class="btn btn-lg btn-outline-dark">Submeter</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card shadow-sm p-4 mb-5">
			<h2 class="text-center">Programação</h2><br>
			<p class="text-center mb-4">Atividades</p>

			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
						<?php
						if (count((array)$atividade["total_dias"][0]) > 0) {
							foreach ($atividade["total_dias"] as $i => $dia) {
						?>
								<li class="nav-item">
									<a class="nav-link <?= $i == 0 ? "active" : "" ?>" id="dia<?= $i ?>-tab" data-toggle="tab" href="#dia<?= $i ?>" role="tab" aria-controls="dia<?= $i ?>" aria-selected="true">
										<?= Util::dia($dia->data) . "/" . Util::mes($dia->data) ?>
									</a>
								</li>
						<?php
							}
						} else {
						?>
							<li class="nav-item">
								<a class="nav-link active" id="dia1-tab" data-toggle="tab" href="#dia1" role="tab" aria-controls="dia1" aria-selected="true">
									Programação
								</a>
							</li>
						<?php
						}
						?>
					</ul>

					<div class="tab-content" id="myTabContent">
						<?php
						foreach ($atividade["total_dias"] as $i => $dia) {
						?>
							<div class="tab-pane fade <?= $i == 0 ? "show active" : "" ?>" id="dia<?= $i ?>" role="tabpanel" aria-labelledby="dia<?= $i ?>-tab">
								<table class="table table-hover table-bordered">
									<thead class="thead-dark">
										<tr>
											<th class="col-md-2">Horário</th>
											<th class="col-md-6">Título</th>
											<th class="col-md-2">Responsável</th>
											<th class="col-md-2">Local</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if (count((array) $atividade["lista_atividades"][0]) > 0) {
											foreach ($atividade["lista_atividades"] as $j => $ativ) {
												if (Util::dia($ativ->datahora_inicio) == Util::dia($dia->data)) {
										?>
													<tr>
														<td class="align-middle">
															<?= Util::hora($ativ->datahora_inicio) . ":" . Util::min($ativ->datahora_inicio) ?> às
															<?= Util::hora($ativ->datahora_termino) . ":" . Util::min($ativ->datahora_termino) ?>
														</td>
														<td class="align-middle"><?= $ativ->titulo ?></td>
														<td class="align-middle"><?= $ativ->responsavel ?></td>
														<td class="align-middle"><?= $ativ->local ?></td>
													</tr>
										<?php
												}
											}
										} else {
										?>
											<tr>
												<td class="text-center" colspan="4">Em Breve!</td>
											</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- Toast Erro Exclusao -->
		<div class="toast" id="msg_exclusao_erro" role="alert" aria-live="assertive" aria-atomic="true" data-delay="4000" style="position: absolute; top: 4rem; right: 1rem;">
			<div class="toast-header">
				<strong class="mr-auto">Houve um erro!</strong>
				<small>Agora</small>
				<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="toast-body">
				Desculpe, não conseguimos excluir o evento, tente novamente.
			</div>
			<div class="card-footer text-muted bg-warning p-1"></div>
		</div>
		<!-- Toast -->
	</div>
</main>

<script>
	
</script>

<?php
$footer = new Footer();
$footer->setJS('assets/js/evento.js');
require_once 'footer.php';
?>

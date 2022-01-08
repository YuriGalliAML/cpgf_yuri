<?php

echo "AML - Teste de qualificação\n";
echo "Vaga analista de desenvolvimento / implantação PLD 2022\n\n";
echo "Yuri Barcellos Galli\n";
echo "Respostas com Código:\n\n";

$csvFile = file('202110_CPGF.csv');

$row = 1;
$valorTotal = 0.0;
$valorTotalSigilosas = 0.0;
$orgaosSigilosos = [];
$movimentacoesSigilosas = [];
$valoresSigilosos = [];
$portadoresSacantes = [];
$saquesPortador = [];
$somaSaques = [];
$orgaosPortador = [];
$favorecidoCompras = [];
$comprasFavorecido = [];

if (($handle = fopen("202110_CPGF.csv", "r")) !== FALSE) {
	$fields = fgetcsv($handle, 10000, ";");
	// print_r($fields);

    while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
        $num = count($data);
        $row++;

		$valor = floatval(str_replace(",", ".", end($data)));

		if($data[9] == "Sigiloso") {
			$valorTotalSigilosas += $valor;

			if(!in_array($data[3], $orgaosSigilosos)) {
				$orgaosSigilosos[] = $data[3];
				$movimentacoesSigilosas[] = 1;
				$valoresSigilosos[] += $valor;
			} else {
				$position = array_search($data[3], $orgaosSigilosos);
				$movimentacoesSigilosas[$position] += 1;
				$valoresSigilosos[$position] += $valor;
			}
		}

		if($data[12] == "SAQUE CASH/ATM BB") {
			if(!in_array($data[9], $portadoresSacantes)) {
				$portadoresSacantes[] = $data[9];
				$saquesPortador[] = 1;
				$somaSaques[] = $valor;
				$orgaosPortador[] = $data[3];
			} else {
				$position = array_search($data[9], $portadoresSacantes);
				$saquesPortador[$position] += 1;
				$somaSaques[$position] += $valor;
			}
		}

		if(($data[12] == "COMPRA A/V - R$ - APRES") ||
		($data[12] == "COMP A/V-SOL DISP C/CLI-R$ ANT VENC")) {
			if(($data[9] != "NAO SE APLICA") &&
			($data[9] != "SEM INFORMACAO") &&
			($data[9] != "Sigiloso")) {
				if(!in_array($data[9], $favorecidoCompras)) {
					$favorecidoCompras[] = $data[9];
					$comprasFavorecido[] = 1;
				} else {
					$position = array_search($data[9], $favorecidoCompras);
					$comprasFavorecido[$position] += 1;
				}
			}
		}

		$valorTotal += $valor;
    }
    fclose($handle);

	// Letra K
	echo "> Letra K - ";
	echo "Valor total: R$ " . str_replace(".", ",", (string)$valorTotal) . "\n";

	// Letra L
	echo "> Letra L - ";
	echo "Valor total (movimentações sigilosas): R$ " . str_replace(".", ",", (string)$valorTotalSigilosas) . "\n";

	// Letra M
	echo "> Letra M - ";

	// Para ver o registro dos órgãos com movimentações sigilosas, descomente as linhas a seguir:
	// print_r($orgaosSigilosos);
	// print_r($movimentacoesSigilosas);
	// print_r($valoresSigilosos);

	$maiorMovimentadorSigiloso = array_search(max($movimentacoesSigilosas), $movimentacoesSigilosas);

	echo "Maior movimentador sigiloso: " .
	$orgaosSigilosos[$maiorMovimentadorSigiloso] .
	". Valor somado: R$ " .
	str_replace(".", ",", (string)$valoresSigilosos[$maiorMovimentadorSigiloso]) . "\n";

	// Letra N
	echo "> Letra N - ";

	// Para ver o registro dos órgãos com movimentações sigilosas, descomente as linhas a seguir:
	// print_r($portadoresSacantes);
	// print_r($saquesPortador);
	// print_r($somaSaques);
	// print_r($orgaosPortador);

	$maiorPortadorSacante = array_search(max($saquesPortador), $saquesPortador);

	echo "Maior portador sacante: " .
	$portadoresSacantes[$maiorPortadorSacante] .
	". Valor somado dos saques: R$ " .
	str_replace(".", ",", (string)$somaSaques[$maiorPortadorSacante]) .
	". Órgão do portador: " .
	str_replace(".", ",", (string)$orgaosPortador[$maiorPortadorSacante]) .
	"\n";

	// Letra O
	echo "> Letra O - ";

	$maisComprasFavorecido = array_search(max($comprasFavorecido), $comprasFavorecido);

	echo "Favorecido com mais compras: " .
	$favorecidoCompras[$maisComprasFavorecido] .
	". Compras realizadas: " .
	str_replace(".", ",", (string)$comprasFavorecido[$maisComprasFavorecido]) . "\n";
}

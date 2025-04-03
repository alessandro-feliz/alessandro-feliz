$descricao = Read-Host -Prompt 'Descreva a ocorr�ncia'
$local = Read-Host -Prompt 'Descreva o local'
$tipo_de_local = Read-Host -Prompt 'Indique o tipo de local (10 - Resid�ncia | 20 - Trabalho | 30 - Via P�blica)'


$postParams = @{
 "nr_codu" = Get-Date -Format "yyyyMMddHHmmssfff"
 "descricao" = $descricao
 "id_tipo_de_local" = $tipo_de_local
 "local" = $local
 "id_concelho" = 10
}

$url = "http://localhost/sata/ocorrencia/inserir.php"

Invoke-RestMethod -Uri $url -Method POST -Body $postParams
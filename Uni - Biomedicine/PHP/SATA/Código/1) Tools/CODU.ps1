$descricao = Read-Host -Prompt 'Descreva a ocorrência'
$local = Read-Host -Prompt 'Descreva o local'
$tipo_de_local = Read-Host -Prompt 'Indique o tipo de local (10 - Residência | 20 - Trabalho | 30 - Via Pública)'


$postParams = @{
 "nr_codu" = Get-Date -Format "yyyyMMddHHmmssfff"
 "descricao" = $descricao
 "id_tipo_de_local" = $tipo_de_local
 "local" = $local
 "id_concelho" = 10
}

$url = "http://localhost/sata/ocorrencia/inserir.php"

Invoke-RestMethod -Uri $url -Method POST -Body $postParams
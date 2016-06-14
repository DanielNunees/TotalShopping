<!DOCTYPE html>
<html>
<head>
	<title>Products</title>
</head>
<body>
	@forelse($products as $product)
		<p><b>Preço:</b> </p>
	@empty
		<p>Nenhum produto cadastrado</p>
	@endforelse

</body>
</html>
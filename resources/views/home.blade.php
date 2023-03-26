<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AIRO Code Challenge</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  </body>
</head>
<body>
	<div class="container mt-3">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{route('handleData')}}">
                	@csrf
					<div class="mb-3">
					  	<label for="age" class="form-label">Age</label>
					  	<input type="number" class="form-control" id="age">
					</div>
					<div class="mb-3">
					  	<label for="currency_id" class="form-label">Currency ID</label>
						<select name="currency_id" id="currency_id" class="form-select">
						  <option value="EUR">EUR</option>
						  <option value="GBP">GBP</option>
						  <option value="USD">USD</option>
						</select>
					</div>
					<div class="mb-3">
					  	<label for="start_date" class="form-label">Start Date</label>
					  	<input type="date" class="form-control" id="start_date">
					</div>
					<div class="mb-3">
					  	<label for="end_date" class="form-label">End date</label>
					  	<input type="date" class="form-control" id="end_date">
					</div>
					<div class="mb-3">
					  	<input type="submit" class="form-control" id="submit">
					</div>
				</form>	
			</div>
		</div>
		@if(isset($data))
			<div class="row">
				<div class="col">
					@if(isset($data->error))
						<div class="alert alert-danger" role="alert">
						  	{{$data->error}}
						</div>
					@endif
					@if(isset($data->total))
						<div class="alert alert-success" role="alert">
						  	<p>Total price: {{$data->total}} {{$data->currency_id}}
						</div>
					@endif
				</div>
			</div>
		@endif
	</div>
</body>
</html>
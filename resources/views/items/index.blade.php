<!DOCTYPE html>
<html lang = "ja">
<head>
	<meta charset = "UTF-8">
	<title>商品一覧</title>
</head>

<body>
	<h1>商品一覧</h1>
{{-- エラーメッセージ --}}
@if ($errors->any())
	<ul style="color:red;">
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>	
		@endforeach
	</ul>
@endif	


	<!-- 登録フォーム -->
	<form action="/items" method="POST">
		@csrf
		<input type="text" name="name" value="{{ old('name') }}">	
		<button type = "submit">登録</button>
	</form>
	<hr>
	<ul>
		@foreach ($items as $item)
			<li>
				{{ $item->id }} : {{ $item->name }}	
				<a href="/items/{{ $item->id }}/edit">編集</a>
				
		<!-- 削除フォーム　-->
				<form action="/items/{{ $item->id }}" method="POST" style="display:inline;">
					@csrf
					@method('DELETE')
					<button type = "submit">削除</button>
				</form>
			</li>
		@endforeach
	</ul>

</body>
</html>
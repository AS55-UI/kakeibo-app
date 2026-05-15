<!DOCTYPE html>
<html lang = "ja">
<head>
	<meta charset = "UTF-8">
	<title>編集</title>
</head>

<body>
	<h1>商品編集</h1>
{{-- エラーメッセージ --}}
@if ($errors->any())
	<ul style="color:red;">
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>	
		@endforeach
	</ul>
@endif	


	<!-- 編集フォーム -->
	<form action="/items/{{ $item->id}}" method="POST">
		@csrf
		@method('PUT')
		<input type="text" name="name" value="{{ $item->name }}">	
		<button type = "submit">更新</button>
	</form>
<p><a href="/items">一覧に戻る</a></p>


</body>
</html>
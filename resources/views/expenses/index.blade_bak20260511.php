<!DOCTYPE html>
<html lang = "ja">
<head>
	<meta charset = "UTF-8">
	<title>家計簿ミニアプリ</title>
</head>

<body>
	<h1>家計簿ミニアプリ</h1>

	<!-- 登録フォーム -->
	<form action="/store" method="POST">
		@csrf
		<input type="date" name="date" required>	
		<input type="text" name="item" placeholder="内容" required>	
		<input type="number" name="amount" placeholder="金額" required>	
		<button type = "submit">追加</button>
	</form>

	<h2>一覧</h2>
	<ul>
		@foreach ($expenses as $expense)
			<li>
				{{ $expense->date }} /	
				{{ $expense->item }} /	
				{{ $expense->amount }} 円	
<!--				<a href="/items/{{ $expense->id }}/edit">編集</a>-->
			</li>	
		@endforeach
	</ul>

	
	<h2>合計:{{ $total }} 円</h2>

</body>
</html>
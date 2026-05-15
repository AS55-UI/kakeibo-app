<!DOCTYPE html>
<html lang = "ja">
<head>
	<meta charset = "UTF-8">
	<title>家計簿ミニアプリ</title>
</head>

<body>
	<h1>家計簿ミニアプリ</h1>

	<!-- 登録フォーム -->
	<form action="/expenses" method="POST">
		@csrf
		<input type="date" name="date" required>	
		<input type="text" name="item" placeholder="内容" required>	
		<input type="number" name="amount" placeholder="金額" required>	
		<button type = "submit">追加</button>
	</form>

	<h2>一覧</h2>

	<table border="1" cellpadding="5">
		<tr>
			<th>日付</th>
			<th>内容</th>
			<th>金額</th>
			<th>操作</th>
		</tr>

		@foreach ($expenses as $expense)
		<tr>
			<td>{{ $expense->date }}</td>
			<td>{{ $expense->item }}</td>
			<td>{{ $expense->amount }}</td>
			<td>
				<a href="/expenses/{{ $expense->id }}/edit">編集</a>
				<form action="/expenses/{{ $expense->id }}" method="POST" style="display:inline;">
				@csrf
				@method('DELETE')
				<button type="submit" onclic="return confirm('削除しますか?')">削除</button>
				<//form>
			</td>
		</tr>
		@endforeach
	</table>
	
	<h2>合計:{{ $total }} 円</h2>

</body>
</html>
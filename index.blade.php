<!DOCTYPE html>
<html lang = "ja">
<head>
	<meta charset = "UTF-8">
	<title>家計簿ミニアプリ</title>
	@if ($errors->any())
		<div style="color:red;">
			<ul>
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
</head>

<body>
	<h1>家計簿ミニアプリ</h1>
	<!-- ログアウト -->
	<form action="{{ route('logout') }}" method="POST">
		@csrf
		<button type = "submit">ログアウト</button>
	</form>

	<!-- 登録フォーム -->
	<form action="/expenses" method="POST" novalidate>
		@csrf
		<input type="date" name="date" value="{{ old('date') }}" required>	
		<input type="text" name="item" value="{{ old('item') }}" placeholder="内容" required>	
		<input type="number" name="amount" value="{{ old('amount') }}" placeholder="金額" required>	
		<input type="text" name="category" value="{{ old('category') }}" placeholder="カテゴリ" required>	
		<button type = "submit">追加</button>
	</form>

	<h2>一覧</h2>
	@if($expenses->isEmpty())
		<p style="color:red;">該当するデータがありません</p>
	@endif
	
	<!-- 検索フォーム -->
	<form action="/expenses" method="GET" novalidate>

		<!-- 月検索 -->
		<input type="month" name="month" value="{{ request('month') }}">	

		<!-- 日付検索 -->
		<input type="date" name="date" value="{{ request('date') }}">	

		<!-- カテゴリ検索 -->
		<input type="text" name="category" value="{{ request('category') }}" placeholder="カテゴリ">	

		<!-- 並び替え(新しい・古い) -->
		<select name="sort">
			<option value="">並び替えなし</option>
			<option value="new" {{ request('sort') == 'new' ? 'selected' : ''}}>新しい順</option>
			<option value="old" {{ request('sort') == 'old' ? 'selected' : ''}}>古い順</option>
		</select>

		<button type = "submit">検索</button>
		
		<!-- リセット -->
		<a href="/expenses" style="margin-left:10px; padding:5px; background:#eee;">リセット</a>
	</form>
	

	<table border="1" cellpadding="5">
		<tr>
			<th>日付</th>
			<th>内容</th>
			<th>金額</th>
			<th>カテゴリ</th>
			<th>操作</th>
		</tr>

		@foreach ($expenses as $expense)
		<tr>
			<td>{{ $expense->date }}</td>
			<td>{{ $expense->item }}</td>
			<td>{{ $expense->amount }}</td>
			<td>{{ $expense->category }}</td>
			<td>
				<!-- 編集-->
				<form action="/expenses/{{ $expense->id }}/edit" method="GET" style="display:inline;">
				<button type="submit">編集</button>
				</form>
				<!-- 削除-->
				<form action="/expenses/{{ $expense->id }}" method="POST" style="display:inline; margin-left:5px;">
				@csrf
				@method('DELETE')
				<button type="submit" onclick="return confirm('削除しますか?')">削除</button>
				</form>
			</td>
		</tr>
		@endforeach
	</table>
	
	<h2>合計:{{ $total }} 円</h2>

	<h2>月ごとの合計</h2>

	<table border="1">
		<tr>
			<th>月</th>
			<th>合計金額</th>
		</th>
		@foreach ($monthlyTotals as $month)
		<tr>
			<td>{{$month->month }}</td>
			<td>{{$month->total }} 円</td>
		</tr>
		@endforeach
	</table>
	
	
	
</body>
</html>
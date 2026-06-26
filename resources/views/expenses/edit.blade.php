<!DOCTYPE html>
<html lang = "ja">
<head>
	<meta charset = "UTF-8">
	<title>家計簿ミニアプリ</title>
</head>

<body>
	<h1>支出編集</h1>

@if ($errors->any())
	<div style="color:red;">
		<ul style="list-style: none; padding-left: 0;">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif




	<!-- 編集フォーム -->
	<form action="/expenses/{{ $expense->id }}" method="POST" novalidate>
		@csrf
		@method('PUT')
		<div>
			<label>日付</label>
			<input type="date" name="date" value="{{ old('date', $expense->date) }}">
		</div>

		<div>
			<label>内容</label>
			<input type="text" name="item" value="{{ old('item', $expense->item) }}">	
		
		</div>

		<div>
			<label>金額</label>
			<input type="number" name="amount" value="{{ old('amount', $expense->amount) }}">	
		
		</div>

		<div>
			<label>カテゴリ</label>
			<input type="text" name="category" value="{{ old('category', $expense->category) }}" piaceholder="カテゴリ">	
		
		</div>

		<button type = "submit">更新</button>

		<button type = "button" onclick="location.href='{{ route('expenses.index') }}'">戻る（キャンセル）</button>
		
	</form>

</expenses戻る>



</body>
</html>
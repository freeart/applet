<?php header('Content-type: text/html; charset=utf-8'); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="/css/common.css" media="all"/>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery.numeric.js"></script>
	<script type="text/javascript" src="/js/jquery.serialize.object.js"></script>
</head>
<body>
<div class="window">
	<div class="body">
		<form accept-charset="utf-8" method="get" class="item-form">

			<fieldset>
				<input type="hidden" name="link" value="<?php echo $_GET['link'] ?>">
				<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
				<input type="hidden" name="site_id" value="<?php echo $_GET['site_id'] ?>">
				<table>
					<colgroup>
						<col width="105">
						<col width="*">
						<col width="90">
					</colgroup>
					<tbody>
					<tr>
						<td colspan="2">

						</td>
						<td rowspan="4" style="vertical-align: middle; text-align: center;">
							<img class="thumb" src="<?php echo str_replace(PHP_EOL, '', $_GET['image']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Цена за единицу</label></td>
						<td>
							<input required="required" class="positive" type="text" name="price"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['price']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Валюта</label></td>
						<td>
							<select required="required" name="currency">
								<option value="" selected="selected">Выберите валюту</option>
								<option
									value="RUB"<?php if ($_GET['currency'] == 'RUB') echo " selected=\"selected\"" ?>>Р
									Рубль
								</option>
								<option
									value="EUR"<?php if ($_GET['currency'] == 'EUR') echo " selected=\"selected\"" ?>>&euro;
									Евро
								</option>
								<option
									value="USD"<?php if ($_GET['currency'] == 'USD') echo " selected=\"selected\"" ?>>$
									Доллар
								</option>
								<option
									value="GBP"<?php if ($_GET['currency'] == 'GBP') echo " selected=\"selected\"" ?>>&pound;
									Фунт стерлингов
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label>Наличие</label></td>
						<td>
							<input required="required" class="positive-integer" type="text" name="in_stock"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['in_stock']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Производитель</label></td>
						<td colspan="2">
							<input required="required" type="text" name="manufacturer"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['manufacturer']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Код продавца</label></td>
						<td colspan="2">
							<input required="required" type="text" name="site_article"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['site_article']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Артикул производителя</label></td>
						<td colspan="2">
							<input required="required" type="text" name="article"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['article']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Дистрибьютор</label></td>
						<td colspan="2">
							<input required="required" type="text" name="distributor"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['distributor']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Тех. документация</label></td>
						<td colspan="2">
							<input type="text" name="dataSheet"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['dataSheet']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Описание</label></td>
						<td colspan="2">
							<input type="text" name="description"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['description']) ?>">
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<hr>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding: 0">
							<table class="inner-table">
								<tr>
									<td width="105"><label>Количество</label></td>
									<td width="50">
										<input required="required" type="text" class="positive-integer" name="number"
											   value="<?php echo str_replace(PHP_EOL, '', $_GET['qty']) ?>">
									</td>

									<td width="*"></td>

									<td width="90"><label>Коэффициент</label></td>
									<td width="50">
										<input type="text" class="positive" name="ratio">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><label>Срок поставки</label></td>
						<td colspan="2">
							<input required="required" type="text" name="order_date">
						</td>
					</tr>
					<tr>
						<td><label>Примечание</label></td>
						<td colspan="2">
							<input type="text" name="note">
						</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right">
							<button id="save" type="submit">Сохранить</button>
						</td>
					</tr>
					</tbody>
				</table>
			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
	$(window).on('load', function () {
		top.postMessage('title::<?php echo str_replace(PHP_EOL, '', $_GET['manufacturer'])  . (!empty($_GET['manufacturer']) && !empty($_GET['article']) ? " - " : "")  . str_replace(PHP_EOL, '', $_GET['article']) ?>', '<?php echo $_GET['domain'] ?>');
		setTimeout(function () {
			top.postMessage('height::' + $('.window').height(), '<?php echo $_GET['domain'] ?>');
		}, 0);

		$(".positive").numeric({ negative: false });
		$(".positive-integer").numeric({ decimal: false, negative: false });
	});

	$('[name="number"]').on('change', function (e) {
		top.postMessage('qty::' + $(this).val(), '<?php echo $_GET['domain'] ?>');
	});

	$('[name="number"]').keydown(function (e) {
		if (e.keyCode == 13) {
			e.preventDefault();
			e.stopPropagation();
			$('.item-form').hide();
		}
	});

	$('.item-form').on('submit', function (e) {
		e.preventDefault();
		var form = $(this).serializeObject();

		top.postMessage('close::', '<?php echo $_GET['domain'] ?>');
	});
</script>
</body>
</html>
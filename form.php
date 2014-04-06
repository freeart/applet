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
				<table>
					<colgroup>
						<col width="85">
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
						<td><label>Price</label></td>
						<td>
							<input required="required" class="positive" type="text" name="price"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['price']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Currency</label></td>
						<td>
							<select required="required" name="currency">
								<option value="" selected="selected">Select currency</option>
								<option
									value="EUR"<?php if ($_GET['currency'] == 'EUR') echo " selected=\"selected\"" ?>>&euro;
									Euro
								</option>
								<option
									value="USD"<?php if ($_GET['currency'] == 'USD') echo " selected=\"selected\"" ?>>$
									US Dollar
								</option>
								<option
									value="GBP"<?php if ($_GET['currency'] == 'GBP') echo " selected=\"selected\"" ?>>&pound;
									Pound Sterling
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label>In stock</label></td>
						<td>
							<input required="required" class="positive-integer" type="text" name="in_stock"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['in_stock']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Manufacturer</label></td>
						<td colspan="2">
							<input required="required" type="text" name="manufacturer"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['manufacturer']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Article</label></td>
						<td colspan="2">
							<input required="required" type="text" name="article"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['article']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Distributor</label></td>
						<td colspan="2">
							<input required="required" type="text" name="distributor"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['distributor']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>DataSheet</label></td>
						<td colspan="2">
							<input type="text" name="dataSheet"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['dataSheet']) ?>">
						</td>
					</tr>
					<tr>
						<td><label>Description</label></td>
						<td colspan="2">
							<input type="text" name="description"
								   value="<?php echo str_replace(PHP_EOL, '', $_GET['description']) ?>">
						</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right">
							<button id="save" type="submit">Share</button>
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
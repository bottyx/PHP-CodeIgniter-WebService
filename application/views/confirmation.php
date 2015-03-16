<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Confirmacion</title>
	<meta name="viewport" content="width=device-width">
</head>
<style>

		html, body, div, span, applet, object, iframe, p, blockquote, pre,
		a, abbr, acronym, address, big, cite, code,
		del, dfn, em, img, ins, kbd, q, s, samp,
		small, strike, strong, sub, sup, tt, var,
		b, u, i, center,
		dl, dt, dd, ol, ul, li,
		fieldset, form, label, legend,
		table, caption, tbody, tfoot, thead, tr, th, td,
		article, aside, canvas, details, embed, 
		figure, figcaption, footer, header, hgroup, 
		menu, nav, output, ruby, section, summary,
		time, mark, audio, video {
			margin: 0;
			padding: 0;
			border: 0;
			font-size: 100%;
			font: inherit;
			vertical-align: baseline;
		}
		/* HTML5 display-role reset for older browsers */
		article, aside, details, figcaption, figure, 
		footer, header, hgroup, menu, nav, section {
			display: block;
		}
		body {
			background-color: #D5D5D5;
			line-height: 1;
		}
		ol, ul {
			list-style: none;
		}
		blockquote, q {
			quotes: none;
		}
		blockquote:before, blockquote:after,
		q:before, q:after {
			content: '';
			content: none;
		}
		table {
			border-collapse: collapse;
			border-spacing: 0;
		}
	#titulo{
		padding-top: 20px;
		padding-bottom: 20px;
		background-color: #0F98BF;
		text-align: center;
		color: #fff;
	}
	#contenido{
		text-align: center;
	}
	.card {
	  padding:1.5rem;
	  box-shadow:0 1px 2px #aaa;
	  background:white;
	  margin:0 1rem 1rem;
	  border-radius:3px;
	  user-select:none;
	  animation:fly-in-from-left .5s 1s ease both;
	  transform-origin:top left;
	}
	.card:nth-child(even){
	  animation-name:fly-in-from-right;
	  animation-delay:1.1s;
	 transform-origin:top right;
	}
</style>
<body>

	<div id="titulo">
		<h3>Confirmacion de Mexi carro</h3><br/>
		<img src="<?php echo base_url();?>assets/bocho.png" width="100" alt="">
	</div>
	<br/>
	
	<section class="card">
	  <label><?php echo $message; ?></label>
	</section>

</body>
</html>
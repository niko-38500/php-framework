<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            padding: 1rem;
        }

        .trace-wrapper:not(:last-child) {
            margin-bottom: 1rem;
        }

        .code-folding {
            cursor: pointer;
            background: grey;
            height: 3rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            border-radius: .5rem .5rem 0 0;
        }

        .code-folding:has(+ .code-container.code-container__folded) {
            animation: 1.3s grow-animation both;
            z-index: 10;
            position: relative;
        }

        .code-folding + .code-container.code-container__folded {
            top: -10px;
        }

        @keyframes grow-animation {
            from {
                border-radius: .5rem .5rem 0 0;
            }
            to {
                border-radius: .5rem .5rem .5rem .5rem;
            }
        }

        .code-folding::before {
            content: "+";
            width: 2rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.6rem;
            transform: rotate(45deg);
            transition: .5s;
        }

        .code-container {
            position:relative;
            width: 100%;
            color: white;
            transition: .5s max-height;
            max-height: 316px;
            overflow: hidden;
        }

        .code-container__folded {
            max-height: 0;
        }

        .code-wrapper {
            background: #363432;
            display: grid;
            grid-template-columns: 2.5fr 58fr;
            width: 100%;
            border-radius: 0 0 .5rem .5rem;
            /*height: 100%;*/
        }

        .code-line {
            display: flex;
            align-items: center;
            height: 1rem;
            padding: .43rem 0;
        }

        .code-line:nth-last-child(1), .code-line:nth-last-child(2) {
            padding-bottom: 1rem;
        }

        .code-line:nth-child(1), .code-line:nth-child(2) {
            padding-top: 1rem;
        }

        .code-line__number {
            justify-content: end;
            border-right: #b03319 1px solid;
            padding-right: .6rem;
        }

        .code-line__text {
            padding-left: .5rem;
        }

        .code-line__error {
            background: rgba(208,35,35,0.87);
        }
    </style>
</head>
<body>
<h1>Error <?=$viewModel->exception->getMessage()?></h1>
<div class="trace-container">
    <?php foreach ($viewModel->trace as $trace): ?>
    <div class="trace-wrapper">
        <div class="code-folding">In file <?=$trace->filePath?></div>
        <div class="code-container">
            <pre class="code-wrapper">
                <?php foreach ($trace->fileLines as $fileLine): ?>
                    <span class="code-line code-line__number<?= $fileLine->isErrorLine ? ' code-line__error' : '' ?>"><?= $fileLine->line ?></span>
                    <span class="code-line code-line__text<?= $fileLine->isErrorLine ? ' code-line__error' : '' ?>"><?= $fileLine->text ?></span>
                <?php endforeach; ?>
            </pre>
        </div>
    </div>
    <?php endforeach ?>
</div>
<script type="application/javascript">
    const foldingCodeBlockElements = document.querySelectorAll('.code-folding');

    console.log(foldingCodeBlockElements)
    foldingCodeBlockElements.forEach(element => {
        element.addEventListener('click', e => {
            e.target.nextElementSibling.classList.toggle('code-container__folded')
        })
    })
</script>
</body>
</html>
{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>跳转提示</title>
  <style>
    body {
    align-items: center;
    background: #708fd4;
    display: flex;
    height: 100%;
    justify-content: center;
    margin: 0;
    position: absolute;
    width: 100%;
    }

    .filter {
      display: none;
    }

    .dots {
      filter: url(#gooeyness);
      padding: 30px;
    }

    .dot {
      background: white;
      border-radius: 50%;
      display: inline-block;
      margin-right: 20px;
      width: 32px;
      height: 32px;
    }

    .dot:first-child {
      animation: FirstDot 3s infinite;
    }

    .dot:nth-child(2) {
      animation: SecondDot 3s infinite;
    }

    .dot:nth-child(3) {
      animation: ThirdDot 3s infinite;
    }

    .dot:nth-child(4) {
      animation: FourthDot 3s infinite;
    }

    .dot:nth-child(5) {
      animation: FifthDot 3s infinite;
    }

    @keyframes FirstDot {
      0% {
        transform: scale(1) translateX(0);
      }
      25% {
        transform: scale(2.5) translateX(0);
      }
      50% {
        transform: scale(1) translateX(0);
      }
      83% {
        transform: scale(1) translateX(240px);
      }
      100% {
        transform: scale(1) translateX(0);
      }
    }

    @keyframes SecondDot {
      0% {
        transform: translateX(0px);
      }
      27% {
        transform: translateX(-40px);
      }
      50% {
        transform: translateX(0px);
      }
      81% {
        transform: translateX(180px);
      }
      100% {
        transform: translateX(0);
      }
    }

    @keyframes ThirdDot {
      0% {
        transform: translateX(0px);
      }
      29% {
        transform: translateX(-100px);
      }
      50% {
        transform: translateX(0px);
      }
      79% {
        transform: translateX(120px);
      }
      100% {
        transform: translateX(0);
      }
    }

    @keyframes FourthDot {
      0% {
        transform: translateX(0px);
      }
      31% {
        transform: translateX(-160px);
      }
      50% {
        transform: translateX(0px);
      }
      77% {
        transform: translateX(60px);
      }
      100% {
        transform: translateX(0);
      }
    }

    @keyframes FifthDot {
      0% {
        transform: scale(1) translateX(0);
      }
      33% {
        transform: scale(1) translateX(-220px);
      }
      50% {
        transform: scale(1) translateX(0);
      }
      75% {
        transform: scale(2.5) translateX(0);
      }
      100% {
        transform: scale(1) translateX(0);
      }
    }

  </style>

</head>

<body>

  <svg class="filter" version="1.1">
    <defs>
      <filter id="gooeyness">
        <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 20 -10" result="gooeyness" />
        <feComposite in="SourceGraphic" in2="gooeyness" operator="atop" />
      </filter>
    </defs>
  </svg>
  <div style="font-size: 50px;color:#AFEEEE;">
    <?php switch ($code) {?>
      <?php case 1:?>
       <p class="success"><?php echo(strip_tags($msg));?></p>
        <?php break;?>
      <?php case 0:?>
        <p class="error"><?php echo(strip_tags($msg));?></p>
        <?php break;?>
    <?php } ?>
  </div>
  <div class="dots">
    <div class="dot mainDot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
  </div>
  <br/>
  <div style="font-size: 50px;color:#AFEEEE;">
    <b id="wait"><?php echo($wait);?></b>
    <a id="href" href="<?php echo($url);?>" style="color:#FFC1C1;">跳转</a>
  </div>
</body>
<script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</html>
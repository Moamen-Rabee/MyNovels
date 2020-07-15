<html>
    <head>
        <meta charset="utf-8"/>
        <title>404</title>
        
        <style type="text/css">
            
            @font-face{
            font-family: FontBold;
            src: url(fonts/DroidKufi-bold.ttf);
            }
            
            
            body{
                background-image: url("images/bg.jpg");
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                margin: 0px;
                padding: 0px;
                
            }
            
            .pagebody{
                width: 100%;
                height: 100%;
                position: relative;
                background: rgba(3, 197, 228, 0.5);
                text-align: center;
                
            }
            
            .tit{
                
                color: #fff;
                font-family: FontBold;
                font-size: 40px;
            }
            
            .err{
                margin: 30px;
                color: #fff;
                font-family: monospace;
                font-size: 200px;
                
            }
            
            
            .btn{
                margin-top: 50px;
                color: #fff;
                font-family: FontBold;
                font-size: 24px;
                background-color: #dc0000e0;
                border: 0px;
                border-radius: 20px;
                padding: 7px 20px;
                cursor: pointer;
            }
            
            .btn:hover{
                background-color: #9e0303e0;
            }
            
            .all-info{
                margin: auto;
                
            }
            
        </style>
    </head>
    <body>
        <div class="pagebody">
            <div class="all-info">
                <br/>
                <h1 class="tit">
                ايوه عايز تروح فين حضرتك يعنى ؟
                </h1>

                <h1 class="err">404</h1>

                <input type="button" onclick="window.history.back();" value="الذهاب الى الصفحة السابقة" class="btn"/>

            </div>
        </div>
    </body>
</html>
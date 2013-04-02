PHPでCommandを楽に開発するためのライブラリ
==========

PHPで簡単なコマンドを実装したいときに便利です。
windowsの人は今すぐそのwindowsマシンにLinux入れるか、アップルストアでMacでも買ってください。


動作環境
------------
php5.4以上  
みなさん、php5.4使いましょうヽ(｀・ω・´)ﾉ ｳﾜｧｧﾝ!

動かし方
------------
examplesディレクトリの中を見てもらえれば解りますが、基本的には以下のような流れになります。
1. オートローダーを設定する
てかcomposer installとかすれば多分勝手にautoloder作られるよ！
2. コマンドクラスのインスタンスを生成する
3. executeする
4. CLIで実行するときに使いたいコマンドのに合わせて引数を渡す

例:~/bin/command
    
    #!/usr/bin/env php
    <?php
        require('../vendor/autoload.php');
        $command = new Polidog\Console\Console($argv);
        $command->addPath(__DIR__.DIRECTORY_SEPARATOR.'Command2/');
        $command->execute();	 


こんな感じのクラスがあったとします。
デフォルトでStringクラスがあるのでStringクラスを実行する事を考えます。

     polidog$ cd ~/bin
     polidog$ ./command string urlencode テスト!
     %E3%83%86%E3%82%B9%E3%83%88%21


って感じでurlencodeされた値が表示されます。



例えば、引数にurlencodeを指定しない場合はこんな感じになるんです。

    polidog$ cd ~/bin
    polidog$ ./command string
        base64	string　指定した文字列をbase64エンコードする
        urlencode	string　URLエンコードをする
        urldecode	string　URLデコードする
        unserialize	string　指定したシリアライズされた配列を普通の配列に戻して出力する
        serialize	string　配列なシンタックスの文字列をシリアライズした値に変更する

コマンドの拡張の仕方
------------
拡張したいコマンド用クラスはどこに置いてもかまいません。
Consoleクラスが生成されたあとにaddPathで使用したいクラスのパスを追加することができます。
examplesのcommandファイルには以下のようなコードが記載されています。
    
    $command->addPath(__DIR__.DIRECTORY_SEPARATOR.'Command2/');

これにより、examples/Command2いかのディレクトリにCommandAbstractを継承したクラスを用意しておけば、そのクラスを使用できます。
また、あたりまえですが、クラス名=ファイル名になっていないと利用できません。
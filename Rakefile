require 'fileutils'

task :phar do |t, args|
  File.open("stub.php", "w") do |f|
    f.write(<<-STUB)
    <?php
    Phar::mapPhar();

    $basePath = 'phar://' . __FILE__ . '/';

    spl_autoload_register(function($class) use ($basePath)
    {
        if (0 !== strpos($class, "Stampie\\\\")) {
            return false;
        }
        $path = str_replace('\\\\', DIRECTORY_SEPARATOR, substr($class, 8));
        $file = $basePath.$path.'.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    });

    __HALT_COMPILER();
    STUB
  end

  system "phar-build -s #{Dir.pwd}/src/Stampie -S #{Dir.pwd}/stub.php --phar #{Dir.pwd}/build/stampie.phar --ns --strip-files '.php$'"

  File.unlink("stub.php")
end

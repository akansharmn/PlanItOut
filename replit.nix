
{ pkgs }: {
  deps = [
    pkgs.nvidia-docker
    pkgs.docker-compose_1
    pkgs.php81
    pkgs.php81Extensions.pdo
    pkgs.php81Extensions.pdo_pgsql
    pkgs.php81Extensions.pgsql
    pkgs.postgresql
  ];
}

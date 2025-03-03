
{ pkgs }: {
  deps = [
    pkgs.php81
    pkgs.php81Extensions.pdo
    pkgs.php81Extensions.pdo_pgsql
    pkgs.postgresql_16
  ];
}

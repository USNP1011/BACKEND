<!DOCTYPE html>
<html lang="en" ng-app="apps" ng-controller="indexController">
  <head>
    <title>Sync Data</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
  </head>

  <body>
    <header>
      <!-- place navbar here -->
    </header>
    <main>
        <h3>Data Sync</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Data</th>
                    <th>Jumlah</th>
                    <th>Jumlah Berhasil</th>
                    <th>Jumlah Gagal</th>
                </tr>
            </thead>
        </table>
    </main>
    <footer>
      <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
      integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
      crossorigin="anonymous"
    ></script>
    <script src="libs/angular/angular.min.js"></script>
    <script src="js/apps.js"></script>
    <script src="js/services/helper.services.js"></script>
    <script src="js/services/auth.services.js"></script>
    <script src="js/services/admin.services.js"></script>
    <script src="js/services/pesan.services.js"></script>
    <script src="js/controllers/admin.controllers.js"></script>
  </body>
</html>

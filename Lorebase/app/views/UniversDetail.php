<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($univers->name); ?></title>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f5f5f5;
      }

      .container {
        max-width: 600px;
        margin: 0 auto;
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }

      h1 {
        margin-top: 0;
      }

      .field {
        margin: 15px 0;
      }

      .field label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
      }

      .field p {
        margin: 0;
        color: #333;
      }

      .buttons {
        display: flex;
        gap: 10px;
        margin-top: 30px;
      }

      .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
      }

      .btn-back {
        background-color: #007bff;
        color: white;
      }

      .btn-back:hover {
        background-color: #0056b3;
      }

      .btn-delete {
        background-color: #dc3545;
        color: white;
      }

      .btn-delete:hover {
        background-color: #c82333;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1><?php echo htmlspecialchars($univers->name); ?></h1>

      <div class="field">
        <label>Description :</label>
        <p>
          <?php echo nl2br(htmlspecialchars($univers->description ?? '—')); ?>
        </p>
      </div>

      <div class="field">
        <label>Date de création :</label>
        <p>
          <?php
            echo $univers->createDate
              ? $univers->createDate->format('d/m/Y')
              : '—';
          ?>
        </p>
      </div>

      <div class="buttons">
        <a href="/univers" class="btn btn-back">Retour</a>
        <button
          type="button"
          class="btn btn-delete"
          onclick="deleteUnivers(<?php echo $univers->id; ?>)"
        >
          Supprimer
        </button>
      </div>
    </div>

    <script>
      function deleteUnivers(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cet univers ?")) {
          fetch(`/univers/${id}`, {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json",
            },
          }).then((response) => {
            if (response.ok) {
              window.location.href = "/univers";
            } else {
              alert("Erreur lors de la suppression");
            }
          });
        }
      }
    </script>
  </body>
</html>

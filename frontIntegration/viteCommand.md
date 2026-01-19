# Compilation front (Vite)

Ce document explique rapidement comment lancer la compilation et le serveur de développement pour le dossier frontIntegration/Lorebase_Front.

## Prérequis

- Node.js >= 16 et npm installés
- Se placer dans le dossier du projet front :

  ```bash
  cd /Lorebase_Front
  ```

## Mode développement (live reload)

Lancer le serveur Vite :

```bash
npm run dev
```

- Ouvrir le navigateur : http://localhost:5173 (ou le port indiqué par Vite)
- Modifications SCSS/JS rechargées automatiquement.

## Build production

Compiler les assets optimisés :

```bash
npm run build
```

- Résultat dans `dist/` (ou `outDir` configuré dans `vite.config.js`).

## Prévisualiser la build locale

Tester le build produit :

```bash
npm run preview
```

- Ouvrir l'URL indiquée par la commande (par défaut http://localhost:5173).

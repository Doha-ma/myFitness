# 🏋️ GYM MANAGEMENT SYSTEM - COMPTES ET IDENTIFIANTS

## 📋 RÉSUMÉ RAPIDE - TOUS LES COMPTES

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| **Admin** | `admin@gym.com` | `password` |
| **Réceptionniste** | `receptionist@gym.com` | `password` |
| **Coach** | `coach@gym.com` | `password` |

**⚠️ IMPORTANT:** Tous les comptes par défaut utilisent le mot de passe: **`password`**

---

## 📋 Comptes par défaut (créés par le seeder)

### 👨‍💼 ADMINISTRATEUR
- **Email:** `admin@gym.com`
- **Mot de passe:** `password`
- **Rôle:** Admin
- **Fonctionnalités:**
  - Créer et gérer le staff (réceptionnistes et coachs)
  - Voir les statistiques (nombre de membres, classes, paiements)
  - Voir les statistiques de paiements (total, ce mois, aujourd'hui)

### 🏢 RÉCEPTIONNISTE
- **Email:** `receptionist@gym.com`
- **Mot de passe:** `password`
- **Rôle:** Réceptionniste
- **Fonctionnalités:**
  - Gérer les membres (créer, modifier, voir la liste)
  - Gérer les paiements (enregistrer, voir la liste)

### 💪 COACH
- **Email:** `coach@gym.com`
- **Mot de passe:** `password`
- **Rôle:** Coach
- **Fonctionnalités:**
  - Créer des classes
  - Voir le nombre de membres inscrits dans chaque classe
  - Gérer les horaires des classes

---

## 🔐 Comment créer de nouveaux comptes staff

### Pour créer un nouveau réceptionniste ou coach:

1. Connectez-vous en tant qu'**Admin** (`admin@gym.com` / `password`)
2. Allez dans **"👥 Gestion Staff"**
3. Cliquez sur **"➕ Ajouter un Staff"**
4. Remplissez le formulaire:
   - **Nom complet**
   - **Email** (sera utilisé pour se connecter)
   - **Rôle** (Réceptionniste ou Coach)
   - **Mot de passe** (sera utilisé pour se connecter)
   - **Confirmer le mot de passe**

### ⚠️ Important:
- L'email et le mot de passe saisis par l'admin dans le formulaire d'ajout de staff sont les identifiants de connexion
- Chaque staff peut se connecter avec son email et le mot de passe défini par l'admin
- L'admin peut modifier ou supprimer n'importe quel staff

---

## 📊 Fonctionnalités par rôle

### ADMIN
- ✅ Dashboard avec statistiques complètes
- ✅ Créer des réceptionnistes
- ✅ Créer des coachs
- ✅ Modifier le staff
- ✅ Supprimer le staff
- ✅ Voir le nombre total de membres
- ✅ Voir le nombre total de classes
- ✅ Voir les statistiques de paiements (total, ce mois, aujourd'hui, nombre de paiements)

### RÉCEPTIONNISTE
- ✅ Dashboard avec aperçu
- ✅ Créer des membres
- ✅ Modifier des membres
- ✅ Voir la liste des membres
- ✅ Enregistrer des paiements
- ✅ Voir la liste des paiements

### COACH
- ✅ Dashboard avec aperçu de ses classes
- ✅ Créer des classes
- ✅ Modifier des classes
- ✅ Voir la liste de ses classes
- ✅ Voir le nombre de membres inscrits dans chaque classe
- ✅ Gérer les horaires des classes

---

## 🚀 Instructions de démarrage

1. **Installer les dépendances:**
   ```bash
   composer install
   ```

2. **Configurer la base de données:**
   - Créer une base de données MySQL
   - Configurer le fichier `.env` avec les informations de connexion

3. **Exécuter les migrations:**
   ```bash
   php artisan migrate
   ```

4. **Créer les comptes par défaut:**
   ```bash
   php artisan db:seed
   ```

5. **Démarrer le serveur:**
   ```bash
   php artisan serve
   ```

6. **Accéder à l'application:**
   - Ouvrir `http://localhost:8000` dans votre navigateur
   - Se connecter avec un des comptes ci-dessus

---

## 📝 Notes importantes

- Tous les comptes par défaut utilisent le mot de passe: **`password`**
- L'admin peut créer de nouveaux staff avec des emails et mots de passe personnalisés
- Les identifiants de connexion sont définis par l'admin lors de la création du staff
- Le système utilise l'email comme identifiant unique pour la connexion

---

## 🔒 Sécurité

- Les mots de passe sont hashés dans la base de données
- Chaque rôle a accès uniquement à ses fonctionnalités spécifiques
- Les routes sont protégées par middleware d'authentification et de rôle

---

**Date de création:** 2026
**Version:** 1.0

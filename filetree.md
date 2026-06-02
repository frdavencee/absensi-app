# File Tree: absensi-app

**Generated:** 5/29/2026, 2:08:34 PM
**Root Path:** `c:\xampp\htdocs\absensi-app`

```
├── 📁 app
│   ├── 📁 Http
│   │   ├── 📁 Controllers
│   │   │   ├── 📁 Auth
│   │   │   │   ├── 🐘 AuthenticatedSessionController.php
│   │   │   │   ├── 🐘 ConfirmablePasswordController.php
│   │   │   │   ├── 🐘 EmailVerificationNotificationController.php
│   │   │   │   ├── 🐘 EmailVerificationPromptController.php
│   │   │   │   ├── 🐘 NewPasswordController.php
│   │   │   │   ├── 🐘 PasswordController.php
│   │   │   │   ├── 🐘 PasswordResetLinkController.php
│   │   │   │   ├── 🐘 RegisteredUserController.php
│   │   │   │   └── 🐘 VerifyEmailController.php
│   │   │   ├── 🐘 AbsensiController.php
│   │   │   ├── 🐘 Controller.php
│   │   │   ├── 🐘 DashboardController.php
│   │   │   ├── 🐘 JadwalController.php
│   │   │   ├── 🐘 KaryawanController.php
│   │   │   ├── 🐘 PengajuanController.php
│   │   │   ├── 🐘 ProfileController.php
│   │   │   └── 🐘 UserController.php
│   │   ├── 📁 Middleware
│   │   │   └── 🐘 RoleMiddleware.php
│   │   ├── 📁 Requests
│   │   │   ├── 📁 Auth
│   │   │   │   └── 🐘 LoginRequest.php
│   │   │   └── 🐘 ProfileUpdateRequest.php
│   │   └── 🐘 Kernel.php
│   ├── 📁 Models
│   │   ├── 🐘 Absensi.php
│   │   ├── 🐘 Jadwal.php
│   │   └── 🐘 User.php
│   ├── 📁 Providers
│   │   └── 🐘 AppServiceProvider.php
│   └── 📁 View
│       └── 📁 Components
│           ├── 🐘 AppLayout.php
│           └── 🐘 GuestLayout.php
├── 📁 bootstrap
│   ├── 🐘 app.php
│   └── 🐘 providers.php
├── 📁 config
│   ├── 🐘 app.php
│   ├── 🐘 auth.php
│   ├── 🐘 cache.php
│   ├── 🐘 database.php
│   ├── 🐘 filesystems.php
│   ├── 🐘 logging.php
│   ├── 🐘 mail.php
│   ├── 🐘 queue.php
│   ├── 🐘 services.php
│   └── 🐘 session.php
├── 📁 database
│   ├── 📁 factories
│   │   └── 🐘 UserFactory.php
│   ├── 📁 migrations
│   │   ├── 🐘 0001_01_01_000000_create_users_table.php
│   │   ├── 🐘 0001_01_01_000001_create_cache_table.php
│   │   ├── 🐘 0001_01_01_000002_create_jobs_table.php
│   │   ├── 🐘 2026_05_22_025506_create_absensis_table.php
│   │   ├── 🐘 2026_05_23_084802_add_location_to_absensis_table.php
│   │   ├── 🐘 2026_05_23_090432_add_role_to_users_table.php
│   │   ├── 🐘 2026_05_23_093652_add_izin_to_absensis_table.php
│   │   ├── 🐘 2026_05_23_103811_add_foto_to_absensis_table.php
│   │   ├── 🐘 2026_05_25_085046_add_foto_pulang_to_absensis_table.php
│   │   ├── 🐘 2026_05_28_153919_add_jadwal_columns_to_absensis_table.php
│   │   └── 🐘 2026_05_28_165206_create_jadwals_table.php
│   ├── 📁 seeders
│   │   └── 🐘 DatabaseSeeder.php
│   ├── ⚙️ .gitignore
│   └── 📄 database.sqlite
├── 📁 public
│   ├── 📁 foto
│   │   ├── 🖼️ pulang_1779694980.jpg
│   │   ├── 🖼️ pulang_1779695619.jpg
│   │   ├── 🖼️ pulang_1779695715.jpg
│   │   ├── 🖼️ pulang_1779695784.jpg
│   │   └── 🖼️ pulang_1779695796.jpg
│   ├── ⚙️ .htaccess
│   ├── 📄 favicon.ico
│   ├── 🐘 index.php
│   └── 📄 robots.txt
├── 📁 resources
│   ├── 📁 css
│   │   └── 🎨 app.css
│   ├── 📁 js
│   │   └── 📄 app.js
│   └── 📁 views
│       ├── 📁 absensi
│       │   ├── 🐘 data.blade.php
│       │   ├── 🐘 index.blade.php
│       │   └── 🐘 pengajuan.blade.php
│       ├── 📁 auth
│       │   ├── 🐘 confirm-password.blade.php
│       │   ├── 🐘 forgot-password.blade.php
│       │   ├── 🐘 login.blade.php
│       │   ├── 🐘 register.blade.php
│       │   ├── 🐘 reset-password.blade.php
│       │   └── 🐘 verify-email.blade.php
│       ├── 📁 components
│       │   ├── 🐘 application-logo.blade.php
│       │   ├── 🐘 auth-session-status.blade.php
│       │   ├── 🐘 danger-button.blade.php
│       │   ├── 🐘 dropdown-link.blade.php
│       │   ├── 🐘 dropdown.blade.php
│       │   ├── 🐘 input-error.blade.php
│       │   ├── 🐘 input-label.blade.php
│       │   ├── 🐘 modal.blade.php
│       │   ├── 🐘 nav-link.blade.php
│       │   ├── 🐘 primary-button.blade.php
│       │   ├── 🐘 responsive-nav-link.blade.php
│       │   ├── 🐘 secondary-button.blade.php
│       │   └── 🐘 text-input.blade.php
│       ├── 📁 jadwal
│       │   ├── 🐘 create.blade.php
│       │   ├── 🐘 edit.blade.php
│       │   └── 🐘 index.blade.php
│       ├── 📁 karyawan
│       │   ├── 🐘 create.blade.php
│       │   ├── 🐘 edit.blade.php
│       │   └── 🐘 index.blade.php
│       ├── 📁 layouts
│       │   ├── 🐘 app.blade.php
│       │   ├── 🐘 guest.blade.php
│       │   └── 🐘 navigation.blade.php
│       ├── 📁 pengajuan
│       │   └── 🐘 index.blade.php
│       ├── 📁 profile
│       │   ├── 📁 partials
│       │   │   ├── 🐘 delete-user-form.blade.php
│       │   │   ├── 🐘 update-password-form.blade.php
│       │   │   └── 🐘 update-profile-information-form.blade.php
│       │   └── 🐘 edit.blade.php
│       ├── 🐘 dashboard.blade.php
│       ├── 🐘 qr.blade.php
│       └── 🐘 welcome.blade.php
├── 📁 routes
│   ├── 🐘 auth.php
│   ├── 🐘 console.php
│   └── 🐘 web.php
├── 📁 storage
│   ├── 📁 app
│   │   ├── 📁 private
│   │   │   └── ⚙️ .gitignore
│   │   ├── 📁 public
│   │   │   └── ⚙️ .gitignore
│   │   └── ⚙️ .gitignore
│   ├── 📁 framework
│   │   ├── 📁 sessions
│   │   │   └── ⚙️ .gitignore
│   │   ├── 📁 testing
│   │   │   └── ⚙️ .gitignore
│   │   ├── 📁 views
│   │   │   ├── ⚙️ .gitignore
│   │   │   ├── 🐘 0201967cfb3609de00336bf106c7da6d.php
│   │   │   ├── 🐘 060ba80030a6038dd4982df0190b591e.php
│   │   │   ├── 🐘 0a2c0f9349d259e9a4a59937c485c0a2.php
│   │   │   ├── 🐘 0a8b641e73dc11b35cc68a029dfc68b4.php
│   │   │   ├── 🐘 14303ba528dd79b1c3069e89c0655f61.php
│   │   │   ├── 🐘 176545ab3da0d7f91f565b4aacf7a7fe.php
│   │   │   ├── 🐘 176cad38599c0662869cf3a3423a5c24.php
│   │   │   ├── 🐘 19a435104a5ba0173242dc71ae6b8318.php
│   │   │   ├── 🐘 21ca2842e70ae381d9224258245068a0.php
│   │   │   ├── 🐘 231513942c5aa7c6289769ce4f1f73c6.php
│   │   │   ├── 🐘 2336d82430483090036cd247cd9ae82c.php
│   │   │   ├── 🐘 27943f1540ad97e78176dd91982f2f3b.php
│   │   │   ├── 🐘 2b763a2bbf6253dcb67aafb3b1163614.php
│   │   │   ├── 🐘 36d47ce335116757e3dfa7d286163526.php
│   │   │   ├── 🐘 37635479d7d8724936ab6dadad2fb08f.php
│   │   │   ├── 🐘 3f603819adb9e2a433f341cf0f7ad7a6.php
│   │   │   ├── 🐘 428e8ab83d94f145b3358c339f96dcf6.php
│   │   │   ├── 🐘 44a92998c62e78211ad5cc03cb66d447.php
│   │   │   ├── 🐘 4602d9cd7875d92c286235889d977245.php
│   │   │   ├── 🐘 4984ae17b501aed5c4f84ab661734343.php
│   │   │   ├── 🐘 4b3e6af430c940365c11cb7b44f0f942.php
│   │   │   ├── 🐘 54454b9ce3ab45588666930ba3939660.php
│   │   │   ├── 🐘 57a3a6f4a6b50049f0e554f3e805af7f.php
│   │   │   ├── 🐘 59b3828af08fa11a045e806632da890a.php
│   │   │   ├── 🐘 5e8a2d5a240a340fe9bf8747ee71670d.php
│   │   │   ├── 🐘 621fac89063c958e09ff316e31214653.php
│   │   │   ├── 🐘 6b364d39e759c41820b46632683a9472.php
│   │   │   ├── 🐘 71e12f4a1a50d506950ee7fe14220505.php
│   │   │   ├── 🐘 75b6bec455a8db9165392a2d5d67abaf.php
│   │   │   ├── 🐘 76a664aa5a93bc020402992cdcb850c6.php
│   │   │   ├── 🐘 77d63ed2cf69c98f40ac731536e79e86.php
│   │   │   ├── 🐘 7abd5de69c61d4e489c9ebefd5e717b8.php
│   │   │   ├── 🐘 7bb36c92629ab0bfdd316692c0ec47f2.php
│   │   │   ├── 🐘 7e04de561e97be11ed3ee6d4ee8a4b51.php
│   │   │   ├── 🐘 7e1de79c6995da7e7ffb284be816e112.php
│   │   │   ├── 🐘 8240a57caa299e38e41451345467444c.php
│   │   │   ├── 🐘 899859a03379ed0b69b8a18576817401.php
│   │   │   ├── 🐘 8da5d74311b7212d2ad082fa133a4155.php
│   │   │   ├── 🐘 8eeca0aee6afac7b3cc7441f4d1f5eaa.php
│   │   │   ├── 🐘 8eede6145eb8d1708f248dfdf4a0c1a3.php
│   │   │   ├── 🐘 8fc3212ddb4536df374c7e8b082989c0.php
│   │   │   ├── 🐘 95b11e64d1de6a4258ecb39d31c198e2.php
│   │   │   ├── 🐘 980911b22a655652a9cda1c559e8e030.php
│   │   │   ├── 🐘 98e0cfdba64aea86d2eb269b90cf4aa4.php
│   │   │   ├── 🐘 9f3eece74929dfac032e79d2b5344522.php
│   │   │   ├── 🐘 a1d6f4a06e996339788ecd2438f6664e.php
│   │   │   ├── 🐘 a37c8fdbc5dae203fdd71c1fa6334c5a.php
│   │   │   ├── 🐘 a8e128ff33bac54997da3749d3b864ac.php
│   │   │   ├── 🐘 ae455ee47f6b063db70293515c13c864.php
│   │   │   ├── 🐘 b4f6c5bd46a81f6892f54d2a6dfe452a.php
│   │   │   ├── 🐘 b939725de06e3cc8205b0a0e3dd1481d.php
│   │   │   ├── 🐘 bf12e6df38681b115e3557bb3868f15a.php
│   │   │   ├── 🐘 bfb555ca4befec1c665976f0dd5ca628.php
│   │   │   ├── 🐘 c145600d39a2fd5f9b87ba2b44d9d1bb.php
│   │   │   ├── 🐘 c15a6264cbaa83e2ca7756b4e0c7976c.php
│   │   │   ├── 🐘 c1b3ab973c33baf27c256f18eb378ecd.php
│   │   │   ├── 🐘 c5ac22a7e1a289dfe6a70b1a50c77a88.php
│   │   │   ├── 🐘 c668bb32368e1d9703e0874c7506508e.php
│   │   │   ├── 🐘 cd008460b59b94b8814d7ceed021a820.php
│   │   │   ├── 🐘 ced1a58ca8864553d020c31fb5c178ae.php
│   │   │   ├── 🐘 d0de66673868c6c32c4fec1e1d6eff37.php
│   │   │   ├── 🐘 d1953c61378a32c854f03c81d95c4eea.php
│   │   │   ├── 🐘 d78d2abc161c66ab23e9f734404fe5be.php
│   │   │   ├── 🐘 db29f819904bc4f8736a8b9dd7b23410.php
│   │   │   ├── 🐘 ee3e55c33227fb76c7a62424c7e1b300.php
│   │   │   ├── 🐘 fc1133babd08cb208890f0af5e9e165d.php
│   │   │   └── 🐘 ff255af290c4c094f02f01eb22b112d1.php
│   │   └── ⚙️ .gitignore
│   └── 📁 logs
│       └── ⚙️ .gitignore
├── 📁 tests
│   ├── 📁 Feature
│   │   ├── 📁 Auth
│   │   │   ├── 🐘 AuthenticationTest.php
│   │   │   ├── 🐘 EmailVerificationTest.php
│   │   │   ├── 🐘 PasswordConfirmationTest.php
│   │   │   ├── 🐘 PasswordResetTest.php
│   │   │   ├── 🐘 PasswordUpdateTest.php
│   │   │   └── 🐘 RegistrationTest.php
│   │   ├── 🐘 ExampleTest.php
│   │   └── 🐘 ProfileTest.php
│   ├── 📁 Unit
│   │   └── 🐘 ExampleTest.php
│   ├── 🐘 Pest.php
│   └── 🐘 TestCase.php
├── ⚙️ .editorconfig
├── ⚙️ .env.example
├── ⚙️ .gitattributes
├── ⚙️ .gitignore
├── ⚙️ .npmrc
├── 📝 README.md
├── 📄 artisan
├── ⚙️ composer.json
├── ⚙️ package-lock.json
├── ⚙️ package.json
├── ⚙️ phpunit.xml
├── 📄 postcss.config.js
├── 📄 tailwind.config.js
└── 📄 vite.config.js
```

---
*Generated by FileTree Pro Extension*
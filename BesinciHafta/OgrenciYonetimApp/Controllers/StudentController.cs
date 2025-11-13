// Controllers/StudentController.cs
public class StudentController : Controller
{
    private readonly StudentRepository _repository = new StudentRepository();

    // 1. Ana Liste (Read) - Verileri Görüntüle
    public IActionResult Index()
    {
        var students = _repository.GetAll();
        return View(students); // View'a öğrenci listesini gönderir
    }

    // --- EKLE (Create) ---

    // 2. Ekleme Formunu Gösteren Metot (GET)
    // URL: /Student/Create
    public IActionResult Create()
    {
        return View(); // Yeni öğrenci ekleme formunu döndürür
    }

    // 3. Formdan Gelen Veriyi Kaydeden Metot (POST)
    [HttpPost]
    [ValidateAntiForgeryToken] // Güvenlik için önerilir
    public IActionResult Create(Student student)
    {
        if (ModelState.IsValid) // Model (veri) geçerliyse (örn. Name boş değilse)
        {
            _repository.Add(student);
            // İşlem başarılı, kullanıcıyı ana sayfaya yönlendir
            return RedirectToAction(nameof(Index));
        }
        // Veri geçerli değilse, formu hatalarla tekrar göster
        return View(student);
    }

    // --- DÜZENLE (Update) ---

    // 4. Düzenleme Formunu Gösteren Metot (GET)
    // URL: /Student/Edit/1
    public IActionResult Edit(int id)
    {
        var student = _repository.GetById(id);
        if (student == null)
        {
            return NotFound(); // 404 Hatası
        }
        return View(student); // Öğrencinin mevcut verilerini formda göster
    }

    // 5. Formdan Gelen Güncel Veriyi Kaydeden Metot (POST)
    [HttpPost]
    [ValidateAntiForgeryToken]
    public IActionResult Edit(int id, Student student)
    {
        if (id != student.Id)
        {
            return NotFound(); // ID'ler uyuşmazsa
        }

        if (ModelState.IsValid)
        {
            try
            {
                _repository.Update(student);
            }
            catch (Exception)
            {
                // Gerçek projede veritabanı hataları yakalanır
                throw;
            }
            return RedirectToAction(nameof(Index));
        }
        return View(student);
    }

    // --- SİL (Delete) ---

    // 6. Silme Onay Ekranı (GET) - İsteğe Bağlı
    // URL: /Student/Delete/1
    public IActionResult Delete(int id)
    {
        var student = _repository.GetById(id);
        if (student == null)
        {
            return NotFound();
        }
        return View(student);
    }

    // 7. Silme İşlemini Gerçekleştiren Metot (POST)
    [HttpPost, ActionName("Delete")] // Metot adı çakışmasını engeller
    [ValidateAntiForgeryToken]
    public IActionResult DeleteConfirmed(int id)
    {
        _repository.Delete(id);
        // İşlem başarılı, kullanıcıyı ana sayfaya yönlendir
        return RedirectToAction(nameof(Index));
    }
}
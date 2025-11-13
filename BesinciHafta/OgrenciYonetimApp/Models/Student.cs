// Models/Student.cs
public class Student
{
    // Primary Key (Birincil Anahtar)
    public int Id { get; set; }

    // Öğrenci Adı
    public string Name { get; set; }

    // Öğrenci Numarası (Tekrarsız olmalı, basitlik için string)
    public string StudentNumber { get; set; }

    // Öğrencinin bölümü (Basitlik için)
    public string Department { get; set; }
}
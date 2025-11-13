// Models/Student.cs
namespace OgrenciYonetimApp.Models // Burası TAM OLARAK OgrenciYonetimApp.Models olmalı
{
    public class Student // Sınıf adı doğru yazılmış olmalı
    {
        public int Id { get; set; }
        public string Name { get; set; }
        public string StudentNumber { get; set; }
        public string Department { get; set; }
    }
}
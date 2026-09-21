/* ============================================================
   FRENNZ.STUFF — data.js
   Data produk contoh + kategori + testimoni (ID + EN). Produk yang
   diubah lewat panel admin disimpan sebagai override di localStorage
   supaya perubahan langsung terlihat di toko.
   Brand: Nike & Adidas (6 + 6), warna disesuaikan palet brand.
   ============================================================ */

const DEFAULT_PRODUCTS = [
  {
    id: "f01", name: "Hoodie Ovania", brand: "Thankinsomnia", category: "Hoodie",
    price: 449000, salePrice: 349000,
    colors: [{name:"Hitam",hex:"#111111"},{name:"Abu",hex:"#9A9691"},{name:"Sail",hex:"#CBBFA4"}],
    sizes: ["S","M","L","XL"], stock: { S: 6, M: 10, L: 8, XL: 2 },
    rating: 4.8, reviews: 156,
    desc: "Hoodie Ovania dari Thankinsomnia dengan fleece 340gsm dan potongan oversized. Nyaman untuk layering harian.",
    descEn: "Hoodie Ovania by Thankinsomnia with 340gsm fleece and oversized cut. Comfortable for daily layering.",
    images: ["/images/products/essential-oversized-hoodie-1.jpg","/images/products/essential-oversized-hoodie-2.jpg","/images/products/essential-oversized-hoodie-3.jpg"],
    tag: "SALE", bestseller: true
  },
  {
    id: "f02", name: "Long Sleeve Polo Stone Island", brand: "Stone Island", category: "T-Shirt",
    price: 299000, salePrice: null,
    colors: [{name:"Putih",hex:"#FFFFFF"},{name:"Navy",hex:"#1E3A5F"}],
    sizes: ["S","M","L","XL"], stock: { S: 14, M: 18, L: 12, XL: 7 },
    rating: 4.9, reviews: 302,
    desc: "Long Sleeve Polo Stone Island dengan compass patch ikonik di lengan dan bahan pique cotton garment-dyed yang lembut dan breathable. Kerah polo rib yang kokoh, potongan regular fit yang premium untuk tampilan smart casual.",
    descEn: "Stone Island Long Sleeve Polo with iconic compass patch on the sleeve and soft breathable garment-dyed pique cotton. Sturdy ribbed polo collar, premium regular fit for smart casual look.",
    images: ["/images/products/signature-tee-heavyweight-1.jpg","/images/products/signature-tee-heavyweight-2.jpg"],
    tag: "NEW", bestseller: true
  },
  {
    id: "f03", name: "Crewneck Stone Island", brand: "Stone Island", category: "Crewneck",
    price: 949000, salePrice: 849000,
    colors: [{name:"Hitam",hex:"#111111"},{name:"Abu",hex:"#9A9691"}],
    sizes: ["M","L","XL"], stock: { M: 6, L: 6, XL: 4 },
    rating: 4.9, reviews: 302,
    desc: "Crewneck Stone Island garment-dyed dengan compass patch ikonik di lengan dan bahan loopback 460gsm. Hangat, breathable, dan potongan regular fit yang premium.",
    descEn: "Stone Island garment-dyed crewneck with iconic compass patch on the sleeve and 460gsm loopback cotton. Warm, breathable, and premium regular fit.",
    images: ["/images/products/minimal-crewneck-1.jpg","/images/products/minimal-crewneck-2.jpg"],
    tag: "SALE", bestseller: true
  },
  {
    id: "f04", name: "Kemeja Lyle and Scott", brand: "Lyle and Scott", category: "Kemeja",
    price: 459000, salePrice: 379000,
    colors: [{name:"Putih",hex:"#FFFFFF"},{name:"Sage",hex:"#9CAF88"}],
    sizes: ["S","M","L","XL"], stock: { S: 4, M: 0, L: 5, XL: 3 },
    rating: 4.5, reviews: 58,
    desc: "Kemeja Lyle and Scott Oxford dengan bordir eagle ikonik di dada, bahan cotton poplin lembut dan breathable. Potongan regular fit yang rapi untuk tampilan smart casual.",
    descEn: "Lyle and Scott Oxford shirt with iconic eagle embroidery at chest, soft breathable cotton poplin. Regular fit for smart casual look.",
    images: ["/images/products/relaxed-fit-shirt-1.jpg","/images/products/relaxed-fit-shirt-2.jpg"],
    tag: "SALE", bestseller: false
  },
  {
    id: "f05", name: "Trackpants Adidas", brand: "Adidas", category: "Celana",
    price: 599000, salePrice: null,
    colors: [{name:"Hitam",hex:"#000000"},{name:"Navy",hex:"#1E3A5F"}],
    sizes: ["29","30","32","34"], stock: { "29": 5, "30": 7, "32": 6, "34": 2 },
    rating: 4.7, reviews: 121,
    desc: "Trackpants Adidas dengan potongan tapered yang nyaman dan 3-stripes ikonik di sisi. Bahan double-knit lembut dengan manset elastis untuk tampilan sporty dan mobilitas harian.",
    descEn: "Adidas trackpants with a comfortable tapered cut and iconic 3-stripes on the sides. Soft double-knit fabric with elastic cuffs for a sporty look and daily mobility.",
    images: ["/images/products/tapered-cargo-pants-1.jpg","/images/products/tapered-cargo-pants-2.jpg"],
    tag: null, bestseller: true
  },
  {
    id: "f06", name: "Denim Pants Dralle", brand: "Thankinsomnia", category: "Celana",
    price: 649000, salePrice: 549000,
    colors: [{name:"Indigo",hex:"#2F3A56"},{name:"Hitam",hex:"#000000"}],
    sizes: ["29","30","32","34"], stock: { "29": 2, "30": 4, "32": 5, "34": 3 },
    rating: 4.7, reviews: 69,
    desc: "Denim Pants Dralle dari Thankinsomnia dengan potongan wide straight dan wash indigo premium. Bahan denim 12oz yang kokoh namun nyaman untuk daily wear.",
    descEn: "Thankinsomnia Denim Pants Dralle with wide straight cut and premium indigo wash. Sturdy 12oz denim yet comfortable for daily wear.",
    images: ["/images/products/wide-straight-denim-1.jpg","/images/products/wide-straight-denim-2.jpg"],
    tag: "SALE", bestseller: false
  },
  {
    id: "f07", name: "Hat Rebellia", brand: "Thankinsomnia", category: "Aksesoris",
    price: 249000, salePrice: null,
    colors: [{name:"Hitam",hex:"#000000"},{name:"Krem",hex:"#F5F0E6"}],
    sizes: ["ALL SIZE"], stock: { "ALL SIZE": 22 },
    rating: 4.8, reviews: 97,
    desc: "Hat Rebellia dari Thankinsomnia dengan desain 6-panel, bordir logo Thankinsomnia dan strap adjustable di belakang. Bahan twill premium yang ringan dan nyaman.",
    descEn: "Thankinsomnia Hat Rebellia with 6-panel design, Thankinsomnia logo embroidery and adjustable rear strap. Premium lightweight twill.",
    images: ["/images/products/structured-cap-1.jpg","/images/products/structured-cap-2.jpg"],
    tag: "NEW", bestseller: false
  },
  {
    id: "f08", name: "Tote Bag Floresca", brand: "Thankinsomnia", category: "Aksesoris",
    price: 199000, salePrice: null,
    colors: [{name:"Natural",hex:"#D8CFB8"}],
    sizes: ["ALL SIZE"], stock: { "ALL SIZE": 30 },
    rating: 4.6, reviews: 44,
    desc: "Tote Bag Floresca dari Thankinsomnia berbahan kanvas 14oz dengan sablon grafis khas Thankinsomnia dan tali panjang yang kokoh. Kapasitas luas untuk daily essentials.",
    descEn: "Thankinsomnia Tote Bag Floresca in 14oz canvas with signature Thankinsomnia graphic print and sturdy long straps. Spacious for daily essentials.",
    images: ["/images/products/canvas-tote-1.jpg","/images/products/canvas-tote-2.jpg"],
    tag: null, bestseller: false
  },
  {
    id: "f09", name: "Tracktop Adidas", brand: "Adidas", category: "Hoodie",
    price: 649000, salePrice: null,
    colors: [{name:"Hitam",hex:"#000000"},{name:"Navy",hex:"#1E3A5F"}],
    sizes: ["S","M","L","XL"], stock: { S: 5, M: 7, L: 6, XL: 3 },
    rating: 4.6, reviews: 39,
    desc: "Tracktop Adidas dengan 3-stripes ikonik di lengan.",
    descEn: "Adidas tracktop with iconic 3-stripes on sleeves.",
    images: ["/images/products/track-jacket-minimal-1.jpg","/images/products/track-jacket-minimal-2.jpg"],
    tag: "NEW", bestseller: false
  },
  {
    id: "f10", name: "Crew Sock Luma", brand: "Thankinsomnia", category: "Aksesoris",
    price: 69000, salePrice: 49000,
    colors: [{name:"Putih",hex:"#FFFFFF"},{name:"Hitam",hex:"#111111"}],
    sizes: ["ALL SIZE"], stock: { "ALL SIZE": 48 },
    rating: 4.8, reviews: 133,
    desc: "Paket 3 kaos kaki Crew Sock Luma dari Thankinsomnia dengan bantalan nyaman untuk pemakaian harian. Tersedia dalam warna Putih dan Hitam.",
    descEn: "Thankinsomnia Crew Sock Luma 3-pack with comfortable cushioning for daily wear. Available in White and Black.",
    images: ["/images/products/ribbed-socks-set-1.jpg","/images/products/ribbed-socks-set-2.jpg"],
    tag: "SALE", bestseller: true
  },
  {
    id: "f11", name: "Tshirt Fosca Black Thankinsomnia", brand: "Thankinsomnia", category: "T-Shirt",
    price: 299000, salePrice: null,
    colors: [{name:"Hitam",hex:"#000000"}],
    sizes: ["S","M","L","XL"], stock: { S: 6, M: 6, L: 6, XL: 6 },
    rating: 4.4, reviews: 27,
    desc: "Tshirt Fosca Black Thankinsomnia boxy-fit garment-washed. Warna Hitam.",
    descEn: "Thankinsomnia Fosca Black boxy garment-washed tee. Black color.",
    images: ["/images/products/boxy-tee-washed-1.jpg","/images/products/boxy-tee-washed-2.jpg"],
    tag: null, bestseller: false
  },
  {
    id: "f12", name: "C.P. Shell-R Google Jacket", brand: "C.P. Company", category: "Hoodie",
    price: 549000, salePrice: null,
    colors: [{name:"Hitam",hex:"#000000"}],
    sizes: ["S","M","L","XL"], stock: { S: 3, M: 4, L: 4, XL: 1 },
    rating: 4.5, reviews: 22,
    desc: "Jaket C.P. Shell-R Google Jacket dari C.P. Company dengan lensa goggle ikonik di lengan. Cocok layering di atas hoodie.",
    descEn: "C.P. Company C.P. Shell-R Google Jacket with iconic goggle lens on the sleeve. Perfect layering over hoodie.",
    images: ["/images/products/quilted-vest-1.jpg","/images/products/quilted-vest-2.jpg"],
    tag: "NEW", bestseller: false
  }
];

const CATEGORIES = [
  { name: "Hoodie", en: "Hoodies", icon: "🧥" },
  { name: "T-Shirt", en: "T-Shirts", icon: "👕" },
  { name: "Crewneck", en: "Crewnecks", icon: "🎽" },
  { name: "Kemeja", en: "Shirts", icon: "👔" },
  { name: "Celana", en: "Pants", icon: "👖" },
  { name: "Aksesoris", en: "Accessories", icon: "🧢" }
];

const TESTIMONIALS = [
  { name: "Aditya R.", role: "Pelanggan sejak 2024", roleEn: "Customer since 2024", text: "Bahannya berat dan jahitannya rapi banget, jauh dari ekspektasi untuk harga segini. Repeat order terus.", textEn: "The fabric is heavyweight and the stitching is immaculate — way beyond my expectations at this price. I keep reordering.", rating: 5 },
  { name: "Nadia P.", role: "Pelanggan setia", roleEn: "Loyal customer", text: "Packaging-nya niat, ukurannya pas sesuai chart, dan pengiriman selalu cepat. Frennz.Stuff langganan tetap saya.", textEn: "The packaging is thoughtful, the sizing matches the chart exactly, and delivery is always fast. Frennz.Stuff is my go-to store.", rating: 5 },
  { name: "Bimo S.", role: "Pembeli pertama", roleEn: "First-time buyer", text: "Awalnya ragu belanja online, tapi setelah terima hoodie-nya langsung suka. Warnanya sama persis dengan foto.", textEn: "I was hesitant about shopping online at first, but the moment I got the hoodie I loved it. The color is exactly like the photos.", rating: 4 },
  { name: "Clara W.", role: "Pelanggan sejak 2023", roleEn: "Customer since 2023", text: "Desainnya simpel tapi ga pasaran. Selalu jadi favorit tiap kali dipakai kondangan santai atau nongkrong.", textEn: "Simple but unique designs. Always my favorite for casual events and hanging out.", rating: 5 }
];

function getProducts() {
  if (window.SERVER_PRODUCTS && window.SERVER_PRODUCTS.length) return window.SERVER_PRODUCTS;
  const stored = localStorage.getItem("frennz_products");
  if (stored) { try { return JSON.parse(stored); } catch (e) {} }
  return DEFAULT_PRODUCTS;
}
function saveProducts(list) { localStorage.setItem("frennz_products", JSON.stringify(list)); }
function getProductById(id) { return getProducts().find(p => String(p.id) === String(id)); }

/* ============================================================
   Warna -> foto: foto produk disimpan per warna dengan urutan
   yang sama seperti daftar warna (colors[0] <-> images[0], dst).
   Contoh produk 1: Hitam->foto 1, Abu->foto 2, Sail->foto 3.
   ============================================================ */
function imageIndexForColor(p, color) {
  if (!p || !p.images || !p.images.length) return 0;
  const idx = (p.colors || []).findIndex(c => c.name === color);
  if (idx < 0) return 0;
  return Math.min(idx, p.images.length - 1);
}

function getCategories() {
  if (window.SERVER_CATEGORIES && window.SERVER_CATEGORIES.length) return window.SERVER_CATEGORIES;
  const stored = localStorage.getItem("frennz_categories");
  if (stored) { try { return JSON.parse(stored); } catch (e) {} }
  return CATEGORIES;
}
function saveCategories(list) { localStorage.setItem("frennz_categories", JSON.stringify(list)); }

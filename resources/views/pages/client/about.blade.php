@extends ('layouts.client.main')
@section('content')
    @include('components.client.alert')
    <div class="container">
        @include('breadcrumbs::bootstrap4')
        <div id="detailsDiv" class="f-gtcol2">
            <header class="f-header">
                <p class="f-bndk"><img src="/gioi-thieu/Content/images/banner.jpg" alt=""></p>
                <p class="f-bnmb"><img src="/gioi-thieu/Content/images/banner-mb.jpg" alt=""></p>
            </header>
            <main class="f-main">
                <section class="f-s2">
                    <div class="f-wrap">
                        <div class="f-row clearfix">
                            <div class="f-col1 wow fadeInUp">
                                <h2 class="f-htit">Giới thiệu chung</h2>
                                <p>Năm 1988, 13 nhà khoa học trẻ thành lập Công ty FPT với mong muốn xây dựng
                                    <strong><i>“một tổ chức kiểu mới, giàu mạnh bằng nỗ lực lao động sáng tạo trong khoa học
                                            kỹ thuật và công nghệ, làm khách hàng hài lòng, góp phần hưng thịnh quốc gia,
                                            đem lại cho mỗi thành viên của mình điều kiện phát triển đầy đủ nhất về tài năng
                                            và một cuộc sống đầy đủ về vật chất, phong phú về tinh thần.”</i></strong></p>
                                <br>
                                <p>Không ngừng đổi mới, liên tục sáng tạo và luôn tiên phong mang lại cho khách hàng các sản
                                    phẩm/ giải pháp/ dịch vụ công nghệ tối ưu nhất đã giúp FPT phát triển mạnh mẽ trong
                                    những năm qua. FPT trở thành công ty công nghệ lớn nhất trong khu vực kinh tế tư nhân
                                    của Việt Nam với hơn <strong>28.000</strong> cán bộ nhân viên, trong đó có
                                    <strong>17.628</strong> nhân sự khối Công nghệ. Đồng thời, FPT cũng là doanh nghiệp dẫn
                                    đầu trong các lĩnh vực: Xuất khẩu phần mềm; Tích hợp hệ thống; Phát triển phần mềm; Dịch
                                    vụ CNTT. Hầu hết các hệ thống thông tin lớn trong các cơ quan nhà nước và các ngành kinh
                                    tế trọng điểm của Việt Nam đều do FPT xây dựng và phát triển.</p><br>
                                <p>FPT sở hữu hạ tầng viễn thông phủ khắp 59/63 tỉnh thành tại Việt Nam và không ngừng mở
                                    rộng hoạt động trên thị trường toàn cầu với 46 văn phòng tại 22 quốc gia và vùng lãnh
                                    thổ bên ngoài Việt Nam.</p>
                                <p>Trong suốt quá trình hoạt động, FPT luôn nỗ lực với mục tiêu cao nhất là mang lại sự hài
                                    lòng cho khách hàng thông qua những dịch vụ, sản phẩm và giải pháp công nghệ tối ưu
                                    nhất. Đồng thời, FPT không ngừng nghiên cứu và tiên phong trong các xu hướng công nghệ
                                    mới góp phần khẳng định vị thế của Việt Nam trong cuộc cách mạng công nghiệp lần thứ 4 -
                                    Cuộc cách mạng số. FPT sẽ tiên phong cung cấp dịch vụ chuyển đổi số toàn diện cho các tổ
                                    chức, doanh nghiệp trên quy mô toàn cầu.</p>
                            </div>
                            <div class="f-col2 wow fadeInUp">
                                <p class="f-dkimg"><img src="/gioi-thieu/Content/images/b1-dk.png?v=323" alt=""></p>
                                <p class="f-ipimg f-ipc2"><img src="/gioi-thieu/Content/images/b1-dk.png?v=323"
                                        alt=""></p>
                                <p class="f-mbimg f-ipc2"><img src="/gioi-thieu/Content/images/b1-dk.png?v=323"
                                        alt=""></p>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="f-s3 f-bg wow fadeInUp">
                    <div class="f-wrap">
                        <div class="f-rtext">
                            <h2 class="f-htit">VĂN HÓA</h2>
                            <p>Văn hóa FPT được gói gọn trong 6 chữ <strong>“TÔN ĐỔI ĐỒNG - CHÍ GƯƠNG SÁNG”</strong>, trong
                                đó: “TÔN ĐỔI ĐỒNG” nghĩa là “Tôn trọng cá nhân - Tinh thần đổi mới - Tinh thần đồng đội”, là
                                những giá trị mà tất cả người FPT đều chia sẻ.</p><br>
                            <p>“CHÍ GƯƠNG SÁNG” nghĩa là “Chí công - Gương mẫu - Sáng suốt”, là những phẩm chất cần có của
                                lãnh đạo FPT.</p><br>
                            <p class="f-ipcmg"><img src="/gioi-thieu/Content/images/b2-dk.jpg" alt=""></p>
                        </div>
                    </div>
                </section>
                <section class="f-s4 wow fadeInUp">
                    <div class="f-wrap">
                        <h2 class="f-htit">ĐỊNH HƯỚNG CÔNG NGHỆ</h2>
                        <p>Cam kết mang lại giá trị cao nhất cho khách hàng, FPT luôn chú trọng đầu tư ngân sách dành cho
                            nghiên cứu và phát triển các công nghệ mới nhất. Trong những năm gần đây, công nghệ
                            <strong>S.M.A.C</strong>(viết tắt của 4 từ Social - Mạng xã hội; Mobile - Công nghệ di động;
                            Analytics - Phân tích Dữ liệu lớn; và Cloud - Điện toán đám mây) được coi là trọng tâm và nền
                            tảng để FPT triển khai, cung cấp các dịch vụ/giải pháp thông minh tới khách hàng.</p><br>
                        <p class="f-tcenter"><img src="/gioi-thieu/Content/images/b3-dk.jpg" alt=""></p>
                    </div>
                </section>
                <section class="f-s5 f-bg wow fadeInUp">
                    <div class="f-wrap">
                        <div class="f-rtext">
                            <h2 class="f-htit">Mạng lưới toàn cầu</h2>
                            <p>Với hệ thống 46 văn phòng tại nước ngoài, chúng tôi có thể cùng lúc sử dụng nguồn lực trên
                                toàn cầu và tại Việt Nam để cung cấp dịch vụ/giải pháp cho khách hàng một cách hiệu quả
                                nhất.</p><br>
                        </div>
                        <p class="f-ipcmg"><img src="/gioi-thieu/Content/images/toancau.png" alt=""></p>
                    </div>
                </section>
                <section class="f-s6 wow fadeInUp">
                    <div class="f-wrap">
                        <h2 class="f-htit">Công ty thành viên</h2>
                        <p>Mô hình hoạt động của FPT bao gồm 07 công ty con và 04 công ty liên kết </p>
                        <p class="f-s6imgdk"><img src="/gioi-thieu/Content/images/sodo2.png" alt=""></p>
                        <p class="f-s6imgip"><img src="/gioi-thieu/Content/images/sodo2.png" alt=""></p>
                        <p class="f-s6imgmb"><img src="/gioi-thieu/Content/images/sodo2.png" alt=""></p>
                    </div>
                </section>
            </main>
        </div>
    </div>
@endsection

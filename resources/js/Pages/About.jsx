import { Link } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import Navigation from '../Components/Navigation';
import Footer from '../Components/Footer';
import '../../css/pages/about.css';

export default function About({ prog, graph, ict, address }) {
    const addr = address && address.length > 0 ? address[0] : null;

    const siteName = 'YHA ACADEMY OF TECHNOLOGY';
    const title = `About ${siteName} - Computer Training Center in Myanmar`;
    const description = 'Learn about YHA ACADEMY OF TECHNOLOGY - our mission to empower individuals with technology skills, our vision for the future, and our core values of integrity, creativity, collaboration, and customer focus.';
    const ogImage = '/image/logo/logo.png';

    return (
        <>
            <Head>
                <title>{title}</title>
                <meta name="description" content={description} />
                <meta name="keywords" content="about YHA ACADEMY OF TECHNOLOGY, computer training center Myanmar, YHA mission, IT education Yangon, Data Science, AI, Machine Learning, Python, R, Mobile Development, Flutter, Dart, React, Vue, Laravel, PHP, JavaScript, MERN Stack, Web Development, MySQL, MongoDB" />
                <meta property="og:title" content={title} />
                <meta property="og:description" content={description} />
                <meta property="og:image" content={ogImage} />
                <meta property="og:url" content={window.location.href} />
                <meta property="og:type" content="website" />
                <meta property="og:site_name" content={siteName} />
                <meta name="twitter:card" content="summary_large_image" />
                <meta name="twitter:title" content={title} />
                <meta name="twitter:description" content={description} />
                <meta name="twitter:image" content={ogImage} />
                <link rel="canonical" href={window.location.href} />
            </Head>
            <div className="frontend-page">
            <Navigation
                prog={prog}
                graph={graph}
                ict={ict}
                contactInfo={{
                    address: addr?.address || '123 University Street, Tech City',
                    phone: addr?.yphNo || '+1 (555) 123-4567',
                    email: addr?.yEmail || 'info@yhauniversity.edu',
                }}
            />

            {/* ========== About Hero Section ========== */}
            <section className="about-hero-section mt-5">
                <div className="container">
                    <div className="about-hero-wrapper">
                        {/* Left - Image */}
                        <div className="about-hero-image">
                            <img
                                src="/image/logo/about.jpg"
                                alt="YHA Computer Training Center"
                            />
                        </div>

                        {/* Right - Content */}
                        <div className="about-hero-content">
                            <h1 className="about-hero-title">
                                Learn about YHA Computer
                            </h1>

                            <h2 className="about-hero-subtitle">
                                YHA Computer Training Center
                            </h2>

                            <p className="about-hero-text">
                              မင်္ဂလာပါ ခင်ဗျား။

ကျွန်တော်တို့ YHA Academy of Technology မှ နွေးထွေးစွာ ကြိုဆိုနှုတ်ဆက်အပ်ပါသည်။

YHA Academy ကို ၂၀၁၇ ခုနှစ်၊ ဇွန်လတွင် စတင်တည်ထောင်ခဲ့ပြီး နည်းပညာနယ်ပယ်မှ Software Developer ဘဝ အတွေ့အကြုံများနှင့် သင်ကြားရေး အတွေ့အကြုံများကို အခြေခံကာ မိမိတို့ လူ့ဘောင်အတွက် စွမ်းဆောင်ရည်မြင့် လူငယ်လူရွယ်များကို မွေးထုတ်ပေးနိုင်ရန် ရည်ရွယ်ဖွင့်လှစ်ခဲ့ပါသည်။

ကျွန်တော်တို့ သင်တန်းကျောင်းတွင်-

ကမ္ဘာ့ထိပ်တန်း တက္ကသိုလ်များ၏ သင်ကြားနည်းစနစ်များနှင့် ပြင်ပပညာရှင်များ၏ အကြံပြုချက်များကို အခြေခံထားသော Programming သင်ရိုးညွှန်းတမ်းများ ဖြစ်ခြင်း၊

ဝါသနာပါရာ နယ်ပယ်အလိုက် လက်တွေ့ Project များကို အခြေပြု သင်ကြားပေးခြင်း၊

ကျောင်းသားတစ်ဦးချင့်စီအတွက် ထိရောက်သော အကြံဉာဏ်နှင့် လမ်းညွှန်မှုများ ပေးအပ်နိုင်ခြင်း စသည့် အားသာချက်များဖြင့် စနစ်တကျ သင်ကြားပေးလျက် ရှိပါသည်။

အနာဂတ် နည်းပညာခရီ်းလမ်းကို ယုံကြည်မှုရှိရှိ အတူတကွ လျှောက်လှမ်းလိုသူ လူငယ်များအားလုံးကို ကြိုဆိုဖိတ်ခေါ်အပ်ပါသည်။
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* ========== Mission / Vision / Values ========== */}
            <section className="about-details-section">
                <div className="container">
                    <div className="about-details-list">
                        {/* Mission */}
                        <div className="about-detail-card">
                            <div className="detail-icon">
                                <i className="fa-solid fa-bullseye"></i>
                            </div>
                            <h3 className="detail-title">Our Mission</h3>
                            <p className="detail-text">
                                To empower individuals and organizations with the knowledge
                                and skills needed to thrive in the ever-evolving world of
                                technology through practical, industry-focused education.To empower individuals and organizations with the knowledge
                                and skills needed to thrive in the ever-evolving world of
                                technology through practical, industry-focused education.To empower individuals and organizations with the knowledge
                                and skills needed to thrive in the ever-evolving world of
                                technology through practical, industry-focused education.To empower individuals and organizations with the knowledge
                                and skills needed to thrive in the ever-evolving world of
                                technology through practical, industry-focused education.To empower individuals and organizations with the knowledge
                                and skills needed to thrive in the ever-evolving world of
                                technology through practical, industry-focused education.
                            </p>
                        </div>

                        {/* Vision */}
                        <div className="about-detail-card">
                            <div className="detail-icon">
                                <i className="fa-solid fa-eye"></i>
                            </div>
                            <h3 className="detail-title">Our Vision</h3>
                            <p className="detail-text">
                                To become the leading destination for technology education,
                                where every student gains the tools and confidence to succeed
                                in a rapidly changing digital world.
                            </p>
                        </div>


                        {/* Why and How choose our class */}
                        <div className="about-detail-card">
                            <div className="detail-icon">
                                <i className="fa-solid fa-eye"></i>
                            </div>
                            <h3 className="detail-title"> ဘာကြောင့် YHA မှာ အတန်းတေ တက်သင့်တာလဲ </h3>
                            <p className="detail-text">
                                To become the leading destination for technology education,
                                where every student gains the tools and confidence to succeed
                                in a rapidly changing digital world.
                            </p>
                        </div>

                    </div>
                </div>
            </section>

            <Footer address={address} />
        </div>
    </>
    );
}

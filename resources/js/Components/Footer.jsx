import { Link } from '@inertiajs/react';

export default function Footer({ address }) {
    const footerAddr = address && address.length > 0 ? address[0] : null;

    return (
        <footer className="edu-footer">
            <div className="container py-5">
                <div className="text-center row gy-4 align-items-stretch text-lg-start">
                    <div className="col-lg-3 col-md-6 d-flex flex-column align-items-center align-items-lg-start justify-content-center h-100">
                        <div className="mb-3 footer-brand">
                            <img src="/image/logo/logo.png" alt="Logo" style={{height: '200px'}} />
                        </div>
                    </div>
                    <div className="col-lg-3 col-md-6 d-flex flex-column align-items-center align-items-lg-start justify-content-center h-100">
                        <h5 className="footer-title">Quick Links</h5>
                        <ul className="p-0 m-0 footer-links">
                            <li><Link href="/">Home</Link></li>
                            <li><Link href="/courses/monthl">Courses</Link></li>
                            <li><Link href="/event">Events</Link></li>
                        </ul>
                    </div>
                    <div className="col-lg-3 col-md-6 d-flex flex-column align-items-center align-items-lg-start justify-content-center h-100">
                        <h5 className="footer-title">Contact</h5>
                        <ul className="p-0 m-0 footer-contact">
                            <li><i className="fa-solid fa-location-dot"></i> {footerAddr?.address ?? '-'}</li>
                            <li><i className="fa-solid fa-phone"></i> {footerAddr?.yphNo ?? '-'}</li>
                            <li><i className="fa-solid fa-envelope"></i> {footerAddr?.yEmail ?? '-'}</li>
                        </ul>
                    </div>
                    <div className="col-lg-3 col-md-6 d-flex flex-column align-items-center align-items-lg-start justify-content-center h-100">
                        <h5 className="footer-title">Follow Us</h5>
                        <div className="mb-3 footer-social">
                            <a href="https://t.me/yha202" aria-label="Telegram" target="_blank"><i className="fa-brands fa-telegram"></i></a>
                            <a href="https://www.facebook.com/yhacomputerhledan" aria-label="Facebook" target="_blank"><i className="fa-brands fa-facebook-f"></i></a>
                            <a href="https://www.youtube.com/channel/UCTwXsN1TMJuEiCuFXacQbkA" aria-label="YouTube" target="_blank"><i className="fa-brands fa-youtube"></i></a>
                        </div>
                        <a href="#contact" className="footer-cta">Join Our Classes</a>
                    </div>
                </div>
                <hr className="my-4 footer-divider" />
                <div className="row">
                    <div className="text-center col small" style={{color: '#ff6b01'}}>
                        &copy; 2024 YHA Computer Training Center. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    );
}

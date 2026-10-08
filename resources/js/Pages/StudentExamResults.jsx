import { useEffect, useState } from "react";
import { Link, router, usePage } from "@inertiajs/react";
import { Head } from '@inertiajs/react';
import StudentLayout from "../Layouts/StudentLayout";
import { prettyDate } from "../lib/dates";
import '../../css/pages/student-exam-results.css';

function FilterSelect({ label, value, options, onChange, allLabel }) {
    return (
        <label className="ser-filter">
            <span className="ser-filter-label">{label}</span>
            <select
                className="ser-filter-select"
                value={value == null ? "" : String(value)}
                onChange={(e) => onChange(e.target.value ? Number(e.target.value) : "")}
            >
                <option value="">{allLabel}</option>
                {options.map((option) => (
                    <option key={option.id} value={option.id}>
                        {option.name}
                    </option>
                ))}
            </select>
        </label>
    );
}

export default function StudentExamResults({ results, courses, filters }) {
    const { url, flash } = usePage();

    const filterTo = (next) => {
        router.get(
            "/student-portal/exam-results",
            Object.fromEntries(Object.entries(next).filter(([, v]) => v)),
            { preserveState: true, preserveScroll: true, replace: true }
        );
    };

    const filtered = Boolean(filters?.course_id);

    return (
        <StudentLayout active="examResults" title="Exam Result" key={url}>
            <Head>
                <title>Exam Results - Student Portal - YHA ACADEMY OF TECHNOLOGY</title>
                <meta name="description" content="View your exam results and grades at YHA ACADEMY OF TECHNOLOGY Student Portal. Track your academic performance across all subjects." />
                <meta name="keywords" content="exam results, grades, student portal, YHA ACADEMY OF TECHNOLOGY, academic performance, scores, marks" />
                <meta property="og:title" content="Exam Results - YHA ACADEMY OF TECHNOLOGY" />
                <meta property="og:description" content="View your exam results and grades at YHA ACADEMY OF TECHNOLOGY Student Portal." />
                <meta property="og:image" content="/image/logo/logo.png" />
                <meta property="og:url" content={window.location.href} />
                <meta property="og:type" content="website" />
                <meta property="og:site_name" content="YHA ACADEMY OF TECHNOLOGY" />
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:title" content="Exam Results - YHA ACADEMY OF TECHNOLOGY" />
                <meta name="twitter:description" content="View your exam results and grades at YHA ACADEMY OF TECHNOLOGY Student Portal." />
                <meta name="twitter:image" content="/image/logo/logo.png" />
                <link rel="canonical" href={window.location.href} />
            </Head>
            <div className="ser-page">
                {flash?.success && (
                    <div className="ser-flash ser-flash-ok" role="status">
                        <i className="fas fa-circle-check"></i> {flash.success}
                    </div>
                )}

                {flash?.error && (
                    <div className="ser-flash ser-flash-bad" role="alert">
                        <i className="fas fa-circle-exclamation"></i> {flash.error}
                    </div>
                )}

                {(courses?.length > 0) && (
                    <div className="ser-filters">
                        <FilterSelect
                            label="Course"
                            value={filters?.course_id}
                            options={courses}
                            allLabel="All courses"
                            onChange={(value) => filterTo({ course_id: value })}
                        />

                        <div className="ser-filters-count">
                            {filtered
                                ? `${results.total ?? 0} shown`
                                : `${results.total ?? 0} result${results.total === 1 ? "" : "s"}`}
                        </div>

                        {filtered && (
                            <Link href="/student-portal/exam-results" className="ser-btn is-quiet" scroll={false}>
                                <i className="fas fa-xmark"></i>
                                Clear
                            </Link>
                        )}
                    </div>
                )}

                {results.data.length === 0 ? (
                    <div className="stu-empty">
                        <div className="stu-empty-icon"><i className="fas fa-clipboard-check"></i></div>
                        <div className="stu-empty-title">
                            {filtered ? "Nothing matches this filter" : "No exam results yet"}
                        </div>
                        <p className="stu-empty-text mt-2 mb-0">
                            {filtered
                                ? "Try another course."
                                : "Your graded exam results will appear here."}
                        </p>
                    </div>
                ) : (
                    <div className="ser-grid">
                        {results.data.map((result) => (
                            <article key={result.id} className="ser-card">
                                <div className="ser-card-head">
                                    <h3 className="ser-card-title">{result.subject_name}</h3>
                                    <div className="ser-card-course">
                                        <i className="fas fa-book-open"></i>
                                        {result.course_name || "—"}
                                    </div>
                                </div>

                                <div className="ser-card-meta">
                                    <span className="ser-meta-item">
                                        <i className="fas fa-calendar"></i>
                                        {result.date_label || "—"}
                                    </span>
                                </div>

                                <div className="ser-score-section">
                                    <div className="ser-score-main">
                                        <span className="ser-score-value">{result.score_label}</span>
                                        {result.grade && (
                                            <span className="ser-grade-badge">{result.grade.name}</span>
                                        )}
                                    </div>
                                    {result.grade && (
                                        <div className="ser-grade-detail">
                                            <span>Grade: {result.grade.name}</span>
                                            <span>(up to {result.grade.score_ceiling})</span>
                                        </div>
                                    )}
                                </div>
                            </article>
                        ))}
                    </div>
                )}

                {results.last_page > 1 && (
                    <nav className="ser-pagination" aria-label="Exam results pagination">
                        {results.current_page > 1 && (
                            <Link
                                href={results.prev_page_url}
                                className="ser-page-link"
                                scroll={false}
                                aria-label="Previous page"
                            >
                                <i className="fas fa-chevron-left"></i>
                            </Link>
                        )}

                        <span className="ser-page-info">
                            Page {results.current_page} of {results.last_page}
                        </span>

                        {results.current_page < results.last_page && (
                            <Link
                                href={results.next_page_url}
                                className="ser-page-link"
                                scroll={false}
                                aria-label="Next page"
                            >
                                <i className="fas fa-chevron-right"></i>
                            </Link>
                        )}
                    </nav>
                )}
            </div>
        </StudentLayout>
    );
}
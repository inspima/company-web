<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:sm="http://www.sitemaps.org/schemas/sitemap/0.9">
<xsl:output method="html" encoding="UTF-8" indent="yes"/>
<xsl:template match="/">
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sitemap — INSPIMA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>body {{ font-family: 'Inter', sans-serif; }}</style>
</head>
<body class="bg-slate-950 text-slate-200 min-h-screen p-8">

    <!-- Header -->
    <div class="max-w-5xl mx-auto mb-10">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-sitemap text-white text-sm"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">INSPIMA Sitemap</h1>
                <p class="text-slate-500 text-xs">XML Sitemap — untuk keperluan indeks mesin pencari Google</p>
            </div>
        </div>

        <!-- Stats bar -->
        <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-3">
                <div class="text-2xl font-black text-blue-400"><xsl:value-of select="count(sm:urlset/sm:url)"/></div>
                <div class="text-xs text-slate-500 mt-0.5 font-medium">Total URL</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-3">
                <div class="text-2xl font-black text-emerald-400">
                    <xsl:value-of select="count(sm:urlset/sm:url[sm:priority = '1.0' or sm:priority = '0.9' or sm:priority = '0.8'])"/>
                </div>
                <div class="text-xs text-slate-500 mt-0.5 font-medium">Halaman Utama</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-3">
                <div class="text-2xl font-black text-orange-400">
                    <xsl:value-of select="count(sm:urlset/sm:url[sm:priority = '0.7'])"/>
                </div>
                <div class="text-xs text-slate-500 mt-0.5 font-medium">Proyek</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-3">
                <div class="text-2xl font-black text-purple-400">
                    <xsl:value-of select="count(sm:urlset/sm:url[sm:priority = '0.6'])"/>
                </div>
                <div class="text-xs text-slate-500 mt-0.5 font-medium">Artikel</div>
            </div>
        </div>
    </div>

    <!-- URL Table -->
    <div class="max-w-5xl mx-auto bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
            <h2 class="font-bold text-sm text-white">Daftar URL</h2>
            <span class="text-xs text-slate-500">Diperbarui otomatis dari database</span>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-800 text-xs uppercase tracking-widest text-slate-500">
                    <th class="text-left px-6 py-3 font-semibold">URL</th>
                    <th class="text-left px-6 py-3 font-semibold hidden sm:table-cell">Last Modified</th>
                    <th class="text-left px-6 py-3 font-semibold hidden md:table-cell">Freq</th>
                    <th class="text-right px-6 py-3 font-semibold">Priority</th>
                </tr>
            </thead>
            <tbody>
                <xsl:for-each select="sm:urlset/sm:url">
                    <xsl:sort select="sm:priority" order="descending" data-type="number"/>
                    <tr class="border-b border-slate-800/60 hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-3.5">
                            <a href="{sm:loc}" target="_blank"
                               class="text-blue-400 hover:text-blue-300 hover:underline font-mono text-xs break-all">
                                <xsl:value-of select="sm:loc"/>
                            </a>
                        </td>
                        <td class="px-6 py-3.5 text-slate-500 text-xs hidden sm:table-cell">
                            <xsl:value-of select="sm:lastmod"/>
                        </td>
                        <td class="px-6 py-3.5 hidden md:table-cell">
                            <span class="text-[10px] font-bold uppercase tracking-widest bg-slate-800 text-slate-400 px-2 py-0.5 rounded">
                                <xsl:value-of select="sm:changefreq"/>
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <xsl:choose>
                                <xsl:when test="sm:priority = '1.0'">
                                    <span class="text-[11px] font-black text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded"><xsl:value-of select="sm:priority"/></span>
                                </xsl:when>
                                <xsl:when test="sm:priority &gt;= 0.8">
                                    <span class="text-[11px] font-black text-blue-400 bg-blue-400/10 px-2 py-0.5 rounded"><xsl:value-of select="sm:priority"/></span>
                                </xsl:when>
                                <xsl:when test="sm:priority &gt;= 0.6">
                                    <span class="text-[11px] font-black text-orange-400 bg-orange-400/10 px-2 py-0.5 rounded"><xsl:value-of select="sm:priority"/></span>
                                </xsl:when>
                                <xsl:otherwise>
                                    <span class="text-[11px] font-black text-slate-400 bg-slate-700 px-2 py-0.5 rounded"><xsl:value-of select="sm:priority"/></span>
                                </xsl:otherwise>
                            </xsl:choose>
                        </td>
                    </tr>
                </xsl:for-each>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="max-w-5xl mx-auto mt-6 text-center text-xs text-slate-700">
        <p>Generated dynamically · <a href="/sitemap.php" class="hover:text-slate-500">sitemap.php</a> · INSPIMA © 2024</p>
    </div>

</body>
</html>
</xsl:template>
</xsl:stylesheet>

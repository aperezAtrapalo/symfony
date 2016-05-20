<?php

/*
 * This file is part of the Makhan package.
 *
 * (c) Fabien Potencier <fabien@makhan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Makhan\Component\Intl\Util;

/**
 * An SVN commit.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class SvnCommit
{
    /**
     * @var \SimpleXMLElement
     */
    private $svnInfo;

    /**
     * Creates a commit from the given "svn info" data.
     *
     * @param \SimpleXMLElement $svnInfo The XML result from the "svn info"
     *                                   command.
     */
    public function __construct(\SimpleXMLElement $svnInfo)
    {
        $this->svnInfo = $svnInfo;
    }

    /**
     * Returns the revision of the commit.
     *
     * @return string The revision of the commit.
     */
    public function getRevision()
    {
        return (string) $this->svnInfo['revision'];
    }

    /**
     * Returns the author of the commit.
     *
     * @return string The author name.
     */
    public function getAuthor()
    {
        return (string) $this->svnInfo->author;
    }

    /**
     * Returns the date of the commit.
     *
     * @return string The commit date.
     */
    public function getDate()
    {
        return (string) $this->svnInfo->date;
    }
}
